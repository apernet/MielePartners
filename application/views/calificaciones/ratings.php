<?php
require_once('main.php');
$rating = new ratings($_POST['widget_id']);

# either return ratings, or process a vote
isset($_POST['fetch']) ? $rating->get_ratings() : $rating->vote();

class ratings extends Main
{

    private $tabla_productos = 'calificaciones_productos';
    private $tabla_accesorios = 'calificaciones_accesorios';
    private $widget_id;
    private $data = array();

    function __construct($wid)
    {
        $this->widget_id = $wid;

        $all = file_get_contents($this->data_file);

        if ($all) {
            $this->data = unserialize($all);
        }
    }





}