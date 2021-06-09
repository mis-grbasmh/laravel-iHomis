<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hdiet extends Model
{
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hdiet';

    //function 
    Public Function getDietTypes(){
      $data = hdiet::where('dietstat','A')->select('dietcode','dietdesc')->orderby('dietdesc','ASC')->get();
      return $data;
    }
    

    //functon to get the description of a prescribed patient diet
    Public Static Function getDietDesc($code){
       return hdiet::where('dietcode',$code)
       ->select('dietdesc')->first('dietdesc');
     }

}
