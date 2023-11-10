<?php

namespace App\Helpers;

use App\Exercisetype;
use App\Exercise;



class Helper {


  public static function getExercisetypeName($id)
  {
      $exercisetype = ExerciseType::find($id);
      return ($exercisetype != NULL )? $exercisetype->name : 'No Type';
  }

  public static function getExerciseNametype($id)
  {
      $exercisetype = ExerciseType::find($id);
      return ($exercisetype != NULL )? $exercisetype->type : 'No Type';
  }

  public static function getMaxTypeSet($id, $maxval)
  {

// SELECT * FROM `homestead`.`exercises`
// WHERE total = (SELECT MAX(total) FROM `homestead`.`exercises` WHERE type_id = 1)
      $maxAmount = Exercise::where('type_id', $id)->max($maxval);
      $maxtype = Exercise::where('type_id', $id)->where($maxval, $maxAmount)->get();

      return $maxtype;
  }

  public static function avgSpeed($d,$t)
  {

    $time = $t;
    $distance = (double)$d;
    $timearr = explode(':',$time);
    $timearr[0] = intval ($timearr[0]) * 3600;
    $timearr[1] = intval ($timearr[1]) * 60;
    $timearr[2] = intval ($timearr[2]);
    $secs = array_sum($timearr);
    if ($secs == 0 || $distance == 0)
    {
      return 0;
    }
    $meters = $distance * 1000;
    $avgsec = $meters / $secs;
    $avg = ($avgsec * 3600) / 1000;
    return number_format($avg, 2, '.', '');

  }

  public static function avgTimeperKm($d,$t)
  {
    $time = $t;
    $distance = (double)$d;

    $timearr = explode(':',$time);
    $timearr[0] = intval ($timearr[0]) * 3600;
    $timearr[1] = intval ($timearr[1]) * 60;
    $timearr[2] = intval ($timearr[2]);
    $secs = array_sum($timearr);
    if ($secs == 0 || $distance == 0)
    {
      return 0;
    }
    $meters = $distance * 1000;
    $avgsec = $meters / $secs;
    $avg = (($secs / $meters) * 1000) / 60;
    return number_format($avg, 2, '.', '');
  }



}
