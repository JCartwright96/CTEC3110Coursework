<?php

namespace M2m;

/**
 *
 * Class used to access the PHP library libchart.
 *
 * Class TemperatureDetailsChartModel
 * @package M2m
 */
class TemperatureDetailsChartModel
{
    private $output_chart_details;
    private $stored_temperature_data;
    private $output_chart_path_and_name;
    private $chart_name;


    public function __construct()
    {
        $this->stored_temperature_data = [];
        $this->output_chart_details = '';
        $this->output_chart_path_and_name = '';
        $this->chart_name = '';
    }

    public function __destruct() {}

    /**
     *
     * Sets the stored_temperature_data with an array.
     *
     * @param array $stored_temperature_data
     */
    public function setStoredTemperatureData(array $stored_temperature_data)
    {
        $this->stored_temperature_data = $stored_temperature_data;
    }


    /**
     * Calls the createChartDetails and makeLineChart functions.
     */
    public function createLineChart()
    {
        $this->createChartDetails();
        $this->makeLineChart();
    }

    /**
     *
     * Function to return output_chart_details.
     *
     * @return string
     */
    public function getLineChartDetails()
    {
        return $this->output_chart_details;
    }


    /**
     *
     * Function to set chart_name, output_chart_detail and output_chart_path_and_name
     *
     */
    private function createChartDetails()
    {
        $start_id = $this->stored_temperature_data[0]->getId();
        $end = end($this->stored_temperature_data);
        $end_id = $end->getId();

        $this->chart_name = 'Message-IDS-' . $end_id . '-' . $start_id;
        $output_chart_name = $this->chart_name . '-linechart.png';

        $output_chart_location = LIB_CHART_OUTPUT_PATH;
        $this->output_chart_details = $output_chart_location . $output_chart_name;
        $this->output_chart_path_and_name = LIB_CHART_FILE_PATH . $output_chart_location . $output_chart_name;


        if (!is_dir($output_chart_location))
        {
            mkdir($output_chart_location, 0755, true);
        }
    }

    /**
     * Accesses the libchart library to create a line chart.
     */
    private function makeLineChart()
    {
        $series_data = $this->stored_temperature_data;

        $chart = new \LineChart();

        $chart->getPlot()->getPalette()->setLineColor(array(new \Color(255, 130, 0), new \Color(255, 255, 255)));
        $series1 = new \XYDataSet();

        foreach (array_reverse($series_data) as $data_row)
        {
            $index = $data_row->getId();
            $datum = $data_row->getHeater();
            $series1->addPoint(new \Point($index, (string)$datum));
        }

        $dataSet = new \XYSeriesDataSet();
        $dataSet->addSerie($this->chart_name, $series1);
        $chart->setDataSet($series1);
        $chart->setTitle($this->chart_name);
        $chart->getPlot()->setGraphCaptionRatio(0.75);
        $chart->render($this->output_chart_path_and_name);
    }
}
