<?php

class CssChart{

    public $type;
    public $height;
    public $labels;
    public $headings;
    public $data_display;
    public $axes;
    public $spacing;
    public $multiple;

    // public $orientation;
    // public $reverse_order;

    public function __construct( 
        $type = 'column', 
        $height = '300px', 
        $labels = true, 
        $headings = true, 
        $data_display = '', 
        $axes = 'show-primary-axis show-data-axes', 
        $spacing = '1', 
        $multiple = false 
    ){      
        $this->type = $type;
        $this->height = $height;
        $this->labels = $labels;
        $this->headings = $headings;
        $this->data_display = $data_display;
        $this->axes = $axes;
        $this->spacing = $spacing;
        $this->multiple = $multiple;
    }


    public function buildTable($table_data, $heading= '', $primay_label = '', $data_label = ''){

        $classes = $this->type;

        $classes .= ( $this->headings === true ) ? ' show-heading' : '';
        $classes .= ( $this->labels === true ) ?  ' show-labels' : '';
        $classes .=  ( !empty($this->axes) ) ? ' ' . $this->axes : '';
        $classes .= ( !empty($this->data_display) ) ?  ' ' . $this->data_display : '';
        $classes .= ( !empty($this->spacing) ) ? ' data-spacing-' . $this->spacing : '';
        
    ?>
        <div class="chart-wrap">
            <table style="height:<?= $this->height ?>;" class="charts-css <?= $classes ?>">
                <caption><?= $heading?></caption>
                <thead><tr><th scope="col"><?= $primay_label ?></th><th scope="col"><?= $data_label ?></th></tr></thead> 
                <tbody>
                    <?php

                    $start = 0;
                    $size = 0;
                    $max = max($table_data);

                    foreach ($table_data as $key => $value) { 
                        $size = $value / $max;
                    ?>
                        <tr>
                            <th scope="row"> <?= $key ?> </th> 
                            <td style="--start:<?= $start ?>; --size:<?= $size ?>;">
                                <span class="data"><?= $value ?></span>
                            </td>
                        </tr> 
                    <?php 
                        $start = $size;
                    } ?>
                </tbody>
            </table>
            <div class="primary-axis"><?= $primay_label ?></div>
            <div class="data-axis"><?= $data_label ?></div>
        </div>
    <?php
    } 
     
    public function setType($type){
        $this->type = $type;
    }

    public function setHeight($height){
        $this->height = $height;
    }

    public function setSpacing($spacing){
        $this->spacing = $spacing;
    }

}

/* 
    // get all the categories from the database
    $cats = get_terms( array(
        'taxonomy' => 'psychiatric_diagnosis',
    )); 


    $psyc_case_data = array();

    foreach ($cats as $cat) {

        // echo '<pre>';
        // print_r($cat);
        // echo '</pre>';
        
        $args['tax_query'] = array(
            array(
                'taxonomy' => "psychiatric_diagnosis",
                'field' => 'slug',
                'terms' => array($cat->slug),
                'operator' => 'IN',
            )
        );

        $cases = get_posts($args);

        $psyc_case_data[$cat->name] = intval( ( count($cases) / $total_cases) * 100 );

    }

    unset($args['tax_query']);


    arsort($psyc_case_data);

    $psyc_case_data = array_slice($psyc_case_data, 0, 10);

    $psycChart = new CssChart();
    // $riskChart->setType('bar');
    $psycChart->setHeight('400px');
    $psycChart->setSpacing('15');
    $psycChart->buildTable($psyc_case_data, 'Most Common Psych DX: ' .$total_cases . ' cases' , 'Psych DX', 'Percent of Cases');

*/