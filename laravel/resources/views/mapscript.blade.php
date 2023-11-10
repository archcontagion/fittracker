

<script type="text/javascript" defer>

      var trackerModules = trackerModules || {};
      trackerModules.routes = {};
      trackerModules.redoHistory = [];
      trackerModules.undoHistory = [];
      trackerModules.tempInteractions = [];



      trackerModules.jsonData = {
        features : []
      };
      trackerModules.routes.raster = new ol.layer.Tile({
        source: new ol.source.OSM()
      });

      trackerModules.routes.loadMap = function(val) {

         if (val != '')
         {
            trackerModules.jsonData = JSON.parse(val);
         }





      if (trackerModules.jsonData.features.length > 0 )
      {

        trackerModules.routes.vectorSource = new ol.source.Vector({
        features: (new ol.format.GeoJSON()).readFeatures(trackerModules.jsonData)
        });



        trackerModules.routes.vector = new ol.layer.Vector({
            source: trackerModules.routes.vectorSource,
            style:  trackerModules.routes.styleFunction
        });


        trackerModules.routes.map =  new ol.Map({
          target: 'map',
          layers: [trackerModules.routes.raster, trackerModules.routes.vector],
          view: new ol.View({
            center: ol.proj.fromLonLat([9.146272872766348, 47.68427202015775]),
            zoom: 14
          })
        });



      }
      else
      {
        trackerModules.routes.vectorSource = new ol.source.Vector({});

        trackerModules.routes.vector = new ol.layer.Vector({
            source: trackerModules.routes.vectorSource,
            style: trackerModules.routes.styleFunction
        });

        trackerModules.routes.map =  new ol.Map({
            target: 'map',
            layers: [trackerModules.routes.raster, trackerModules.routes.vector],
            view: new ol.View({
              center: ol.proj.fromLonLat([9.146272872766348, 47.68427202015775]),
              zoom: 14
            })
        });

      }

      // fill undo history
      var features = trackerModules.routes.vectorSource.getFeatures();
      var newForm = new ol.format.GeoJSON();
      var featColl = newForm.writeFeaturesObject(features);
      if (featColl.features[0] != undefined)
      {
        trackerModules.undoHistory = featColl.features[0].geometry.coordinates;
      }


      }

      trackerModules.routes.styleFunction = function(feature) {
        trackerModules.routes.geometry = feature.getGeometry();
        trackerModules.routes.styles = [
          // linestring
          new ol.style.Style({
              fill: new ol.style.Fill({
                color: 'rgba(255, 255, 255, 0.2)'
              }),
              stroke: new ol.style.Stroke({
                color: '#ff0000',
                width: 2
              }),
              image: new ol.style.Circle({
                radius: 7,
                fill: new ol.style.Fill({
                  color: '#ff0000'
                })
              })
          })
        ];

        trackerModules.routes.geometry.forEachSegment(function(start, end) {
          var dx = end[0] - start[0];
          var dy = end[1] - start[1];
          var rotation = Math.atan2(dy, dx);
          // arrows
           trackerModules.routes.styles.push(new ol.style.Style({
            geometry: new ol.geom.Point(end),
             stroke: new ol.style.Stroke({
              color: '#ff0000',
              width: 2,
              anchor: [0.75, 0.5],
              rotateWithView: false,
              rotation: -rotation
            })
          }));
        });

        return  trackerModules.routes.styles;
      };


      trackerModules.routes.loadMap($('#routescoordinates').val());

      trackerModules.routes.map.on('click', function(evt){

        var features = trackerModules.routes.vectorSource.getFeatures();
        var newForm = new ol.format.GeoJSON();
        var featColl = newForm.writeFeaturesObject(features);
        if (featColl.features[0] != undefined)
        {
          trackerModules.undoHistory = featColl.features[0].geometry.coordinates;
        }

});



      /**
       * Currently drawn feature.
       * @type {ol.Feature}
       */
      var sketch;


      /**
       * The help tooltip element.
       * @type {Element}
       */
      var helpTooltipElement;


      /**
       * Overlay to show the help messages.
       * @type {ol.Overlay}
       */
      var helpTooltip;


      /**
       * The measure tooltip element.
       * @type {Element}
       */
      var measureTooltipElement;


      /**
       * Overlay to show the measurement.
       * @type {ol.Overlay}
       */
      var measureTooltip;


      /**
       * Message to show when the user is drawing a polygon.
       * @type {string}
       */
      var continuePolygonMsg = 'Click to continue drawing the polygon';


      /**
       * Message to show when the user is drawing a line.
       * @type {string}
       */
      var continueLineMsg = 'Click to continue drawing the line';


      /**
       * Handle pointer move.
       * @param {ol.MapBrowserEvent} evt The event.
       */
      var pointerMoveHandler = function(evt) {
        if (evt.dragging) {
          return;
        }
        /** @type {string} */
        var helpMsg = 'Click to start drawing';

        if (sketch) {
          var geom = (sketch.getGeometry());
          if (geom instanceof ol.geom.Polygon) {
            helpMsg = continuePolygonMsg;
          } else if (geom instanceof ol.geom.LineString) {
            helpMsg = continueLineMsg;
          }
        }

        helpTooltipElement.innerHTML = helpMsg;
        helpTooltip.setPosition(evt.coordinate);

        helpTooltipElement.classList.remove('hidden');
      };


      var map = trackerModules.routes.map;
      trackerModules.tempInteractions =[];

      map.getInteractions().forEach(function(interaction) {
       if (trackerModules.tempInteractions.length <= 0)
       {
        trackerModules.tempInteractions.push(interaction);
       }

       }, this);

      var mapSwitch = $('input[name="mapswitch"]');

      var source = trackerModules.routes.vectorSource;
      var geodesicCheckbox = document.getElementById('geodesic');



      var geodesicCheckbox = document.getElementById('geodesic');

      var draw; // global so we can remove it later

      var loadEdit = function(mode,overlay) {
        if(mode == true)
        {
          map.on('pointermove', pointerMoveHandler);
          map.getViewport().addEventListener('mouseout', function() {
            helpTooltipElement.classList.add('hidden');
          });
          addInteraction();
          if(overlay == true)
          {
            addOverlay();
          }
        }
        else
        {
          map.un('pointermove', pointerMoveHandler);
          map.getViewport().removeEventListener('mouseout', function() {
            helpTooltipElement.classList.add('hidden');
          });
          removeInteraction();
          if (overlay == true)
          {
            removeOverlay();
          }
        }
      };

      map.ovl ='';
      var addOverlay = function(){
        document.getElementById('mapeditButtons').removeAttribute('style');
        var coord = $('#routescoordinates').val();
        map.ovl = document.getElementById('mapeditButtons');
        if ($('#mapeditButtons','.ol-overlaycontainer').length == 0)
        {
          $(map.ovl).before(
            $('.ol-overlaycontainer')
          );
        }
        else if ($(map.ovl).css('display') == 'none')
        {
          $(map.ovl).show();
        }
      };

      var removeOverlay = function(){
       $(map.ovl).hide();
      };

      $('#undolastMapLine').on('click',function(){
            undoLast($(this), $('#redolastMapLine'));
      });

      $('#redolastMapLine').on('click',function(){
            redoLast($(this),$('#undolastMapLine'));
      });
      if (trackerModules.redoHistory.length == 0)
      {
        $('#redolastMapLine').attr('disabled','disabled');
      }

      $('#mapeditButtons').hover(
        function(){
          loadEdit(false,false);
        },
        function(){
          loadEdit(true,false);
      });


      var undoLast = function($ob,$redo){

        var features = trackerModules.routes.vectorSource.getFeatures();
        var newForm = new ol.format.GeoJSON();
        var featColl = newForm.writeFeaturesObject(features);


          if (trackerModules.undoHistory.length == 2)
          {
            trackerModules.redoHistory.push(trackerModules.undoHistory[0]);
            trackerModules.undoHistory.splice(0,1);
            trackerModules.redoHistory.push(trackerModules.undoHistory[0]);
            trackerModules.undoHistory.splice(0,1);
            $ob.attr('disabled','disabled');
            if($redo.is('[disabled=disabled]'))
            {
              $redo.removeAttr('disabled');
            }
          }
          else
          {
            trackerModules.redoHistory.push(trackerModules.undoHistory[0]);
            trackerModules.undoHistory.splice(0,1);
          }

          if(trackerModules.redoHistory.length == 0 && (!$redo.is('[disabled=disabled]')))
          {
            $redo.attr('disabled','disabled');
          }
          if(trackerModules.undoHistory.length > 0 && $redo.is('[disabled=disabled]'))
          {
            $redo.removeAttr('disabled');
          }


          featColl.features[0].geometry.coordinates = trackerModules.undoHistory;

          trackerModules.routes.vectorSource = new ol.source.Vector({
            features: (new ol.format.GeoJSON()).readFeatures(featColl)
          });

          trackerModules.routes.vector.setSource(trackerModules.routes.vectorSource);
          map.updateSize();
      };

      $(mapSwitch).on('click',function(){
          if($(mapSwitch.html()+':checked').val() == 'edit')
          {
            loadEdit(true,true);
          }
          else
          {
            loadEdit(false,true);
          }
      });

      var redoLast = function($ob,$undo){

          var features = trackerModules.routes.vectorSource.getFeatures();
          var newForm = new ol.format.GeoJSON();
          var featColl = newForm.writeFeaturesObject(features);


          if (trackerModules.undoHistory.length == 0)
          {
            trackerModules.undoHistory.unshift(trackerModules.redoHistory[trackerModules.redoHistory.length-1]);
            trackerModules.redoHistory.pop();
            trackerModules.undoHistory.unshift(trackerModules.redoHistory[trackerModules.redoHistory.length-1]);
            trackerModules.redoHistory.pop();
          }
          else
          {
            trackerModules.undoHistory.unshift(trackerModules.redoHistory[trackerModules.redoHistory.length-1]);
            trackerModules.redoHistory.pop();
          }
          if(trackerModules.redoHistory.length == 0)
          {
             $ob.attr('disabled','disabled');
          }

          $undo.removeAttr('disabled');

          featColl.features[0].geometry.coordinates = trackerModules.undoHistory;

          trackerModules.routes.vectorSource = new ol.source.Vector({
            features: (new ol.format.GeoJSON()).readFeatures(featColl)
          });

          trackerModules.routes.vector.setSource(trackerModules.routes.vectorSource);
          map.updateSize();
      };

      /**
       * Format length output.
       * @param {ol.geom.LineString} line The line.
       * @return {string} The formatted length.
       */
      var formatLength = function(line) {
        var length;
        if (geodesicCheckbox.checked) {
          var coordinates = line.getCoordinates();
          length = 0;
          var sourceProj = map.getView().getProjection();
          var wgs84Sphere = new ol.Sphere(6378137);
          for (var i = 0, ii = coordinates.length - 1; i < ii; ++i) {
            var c1 = ol.proj.transform(coordinates[i], sourceProj, 'EPSG:4326');
            var c2 = ol.proj.transform(coordinates[i + 1], sourceProj, 'EPSG:4326');
            length +=  wgs84Sphere.haversineDistance(c1, c2);
          }
        } else {
          length = Math.round(line.getLength() * 100) / 100;
        }
        var output;
        if (length > 100) {

          output = (Math.round(length / 1000 * 100) / 100) +
              ' ' + 'km';
        } else {
          output = (Math.round(length * 100) / 100) +
              ' ' + 'm';
        }
        return output;
      };


      /**
       * Format area output.
       * @param {ol.geom.Polygon} polygon The polygon.
       * @return {string} Formatted area.
       */
      var formatArea = function(polygon) {
        var area;
        if (geodesicCheckbox.checked) {
          var sourceProj = map.getView().getProjection();
          var geom = /** @type {ol.geom.Polygon} */(polygon.clone().transform(
              sourceProj, 'EPSG:4326'));
          var coordinates = geom.getLinearRing(0).getCoordinates();
          area = Math.abs(wgs84Sphere.geodesicArea(coordinates));
        } else {
          area = polygon.getArea();
        }
        var output;
        if (area > 10000) {
          output = (Math.round(area / 1000000 * 100) / 100) +
              ' ' + 'km<sup>2</sup>';
        } else {
          output = (Math.round(area * 100) / 100) +
              ' ' + 'm<sup>2</sup>';
        }
        return output;
      };
      function removeInteraction(){
         map.removeInteraction(draw);
      }


      function addInteraction() {
        var type = 'LineString';
        draw = new ol.interaction.Draw({
          source: source,
          type: /** @type {ol.geom.GeometryType} */ (type),
          style: new ol.style.Style({
            fill: new ol.style.Fill({
              color: 'rgba(255, 255, 255, 0.2)'
            }),
            stroke: new ol.style.Stroke({
              color: 'rgba(0, 0, 0, 0.5)',
              lineDash: [10, 10],
              width: 2
            }),
            image: new ol.style.Circle({
              radius: 5,
              stroke: new ol.style.Stroke({
                color: 'rgba(0, 0, 0, 0.7)'
              }),
              fill: new ol.style.Fill({
                color: 'rgba(255, 255, 255, 0.2)'
              })
            })
          })
        });
        map.addInteraction(draw);

        createMeasureTooltip();
        createHelpTooltip();

        var listener;
        draw.on('drawstart',
            function(evt) {
              // set sketch
              var features = trackerModules.routes.vectorSource.getFeatures();
              var newForm = new ol.format.GeoJSON();
              var featColl = newForm.writeFeaturesObject(features);

              if(featColl.features[0] != undefined)
              {
                sketch = features[0];
              }
              else
              {
                sketch = evt.feature;
              }

              /** @type {ol.Coordinate|undefined} */
              var tooltipCoord = evt.coordinate;

              listener = sketch.getGeometry().on('change', function(evt) {
                var geom = evt.target;
                var output;
                if (geom instanceof ol.geom.Polygon) {
                  output = formatArea(geom);
                  tooltipCoord = geom.getInteriorPoint().getCoordinates();
                } else if (geom instanceof ol.geom.LineString) {
                  output = formatLength(geom);
                  tooltipCoord = geom.getLastCoordinate();

                    trackerModules.routes.map.saveDistance(output.substring(0,output.indexOf(' ')));

                }
                measureTooltipElement.innerHTML = output;
                measureTooltip.setPosition(tooltipCoord);
              });
            }, this);

        draw.on('drawend',
            function() {

              measureTooltipElement.className = 'tooltip tooltip-static';
              measureTooltip.setOffset([0, -7]);
              // unset sketch
              sketch = null;
              // unset tooltip so that a new one can be created
              measureTooltipElement = null;
              createMeasureTooltip();
              ol.Observable.unByKey(listener);

            }, this);
      }


      /**
       * Creates a new help tooltip
       */
      function createHelpTooltip() {
        if (helpTooltipElement) {
          helpTooltipElement.parentNode.removeChild(helpTooltipElement);
        }
        helpTooltipElement = document.createElement('div');
        helpTooltipElement.className = 'tooltip hidden';
        helpTooltip = new ol.Overlay({
          element: helpTooltipElement,
          offset: [15, 0],
          positioning: 'center-left'
        });
        map.addOverlay(helpTooltip);
      }


      /**
       * Creates a new measure tooltip
       */
      function createMeasureTooltip() {
        if (measureTooltipElement) {
          measureTooltipElement.parentNode.removeChild(measureTooltipElement);
        }
        measureTooltipElement = document.createElement('div');
        measureTooltipElement.className = 'tooltip tooltip-measure';
        measureTooltip = new ol.Overlay({
          element: measureTooltipElement,
          offset: [0, -15],
          positioning: 'bottom-center'
        });
        map.addOverlay(measureTooltip);
      }


// trackerModules.routes.map.addInteraction(new ol.interaction.Draw({
//         source: trackerModules.routes.vectorSource,
//         type: /** @type {ol.geom.GeometryType} */ ('LineString')
// }));

trackerModules.routes.map.saveDistance = function(dist){
  $('#routesdistance').val(dist);
}

trackerModules.routes.map.saveRouteInfo  = function() {

  var features = trackerModules.routes.vectorSource.getFeatures();
  var newForm = new ol.format.GeoJSON();
  var featColl = newForm.writeFeaturesObject(features);

  return (featColl.features.length != 0) ? JSON.stringify(featColl)  : false;
};

(function(){



  $('#map').on('mouseleave',function(){

    if (trackerModules.routes.map.saveRouteInfo() != false || $('#routescoordinates').val().length < 0)
    {
     $('#routescoordinates').val(trackerModules.routes.map.saveRouteInfo);
    }
    else if ($('#routescoordinates').val() == undefined)
    {
      $('#routescoordinates').val(0);
    }
  });

  $('#removeRoute').on('click',function(){

     $('#routescoordinates').val('');
     $('#routesdistance').val('');
     trackerModules.jsonData ={
        features : []
      };
      window.location.reload();
  });

  if($(mapSwitch.html()+':checked').val() == 'edit')
  {
    loadEdit(true,true);
  }
  else
  {
    loadEdit(false,true);
  }
})();

</script>
