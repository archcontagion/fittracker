@extends('layouts.app')

@section('title', 'Edit Workout')

@section('nav')

<li>
<a title="Back to Workoutlist" href="{{  redirect()->back()->getTargetUrl() }}">
  <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
</a>
</li>


@endsection
@section('content')

    <div id="map" class="map"></div>

    <script type="text/javascript">


      var trackerModules = trackerModules || {};
      trackerModules.routes = {};

      trackerModules.routes.raster = new ol.layer.Tile({
        source: new ol.source.OSM()
      });

      trackerModules.routes.source = new ol.source.Vector();

      trackerModules.routes.styleFunction = function(feature) {
        trackerModules.routes.geometry = feature.getGeometry();
        trackerModules.routes.styles = [
          // linestring
          new ol.style.Style({
            stroke: new ol.style.Stroke({
              color: '#ffcc33',
              width: 2
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
              color: '#ffcc33',
              width: 2,
              anchor: [0.75, 0.5],
              rotateWithView: false,
              rotation: -rotation
            })
          }));
        });

        return  trackerModules.routes.styles;
      };
      trackerModules.routes.vector = new ol.layer.Vector({
        source: trackerModules.routes.source,
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
trackerModules.routes.map.on('click', function(evt){
/*      var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
      var lon = lonlat[0];
      var lat = lonlat[1];
      console.log(lat,lon);*/

});
trackerModules.routes.map.addInteraction(new ol.interaction.Draw({
        source: trackerModules.routes.source,
        type: /** @type {ol.geom.GeometryType} */ ('LineString')
}));

trackerModules.routes.map.saveRouteInfo() {
  return trackerModules.routes.geometry.getCoordinates();
}

    </script>
@endsection
