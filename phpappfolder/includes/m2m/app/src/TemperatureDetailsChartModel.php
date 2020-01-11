<?php
/**
 * @author CF Ingrams - cfi@dmu.ac.uk
 * @copyright De Montfort University
 *
 * @package stock-quotes-charts
 */

namespace M2m;

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

    public function setStoredTemperatureData(array $stored_temperature_data)
    {
        $this->stored_temperature_data = $stored_temperature_data;
    }

    public function createLineChart()
    {
        $this->createChartDetails();
        $this->makeLineChart();
    }

    public function getLineChartDetails()
    {
        return $this->output_chart_details;
    }

    private function createChartDetails()
    {
        $this->chart_name = $this->stored_temperature_data['name'];

        $output_chart_name = $this->chart_name . '-linechart.png';
        $output_chart_location = LIB_CHART_OUTPUT_PATH;
        $this->output_chart_details = LANDING_PAGE . DIRSEP . $output_chart_location . $output_chart_name;
        $this->output_chart_path_and_name = LIB_CHART_FILE_PATH . $output_chart_location . $output_chart_name;

        if (!is_dir($output_chart_location))
        {
            mkdir($output_chart_location, 0755, true);
        }
    }

    private function makeLineChart()
    {
        $series_data = $this->stored_temperature_data['temperature-data'];

        $chart = new \LineChart();
        $obj_bound = new \Bound();

        $chart->getPlot()->getPalette()->setLineColor(array(new \Color(255, 130, 0), new \Color(255, 255, 255)));
        $series1 = new \XYDataSet();
        foreach ($series_data as $data_row)
        {
            $index = $data_row['date'];
            $datum = $data_row['value'];
            $series1->addPoint(new \Point($index, $datum));
        }

        $dataSet = new \XYSeriesDataSet();
        $dataSet->addSerie($this->chart_name, $series1);

        $chart->setDataSet($dataSet);

        $obj_bound->computeBound($dataSet);
        $obj_bound->setLowerBound(12);
        $obj_bound->setUpperBound(14);

        $chart->setTitle($this->chart_name);
        $chart->getPlot()->setGraphCaptionRatio(0.75);

        $chart->render($this->output_chart_path_and_name);
    }
}
