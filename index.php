<?php

require('bootstrap.php');

echo '<pre>';

$inputFileType = 'CSV';
$inputFileName = 'input/BOM.csv';
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcel = $objReader->load($inputFileName);


//***************** Example 1, write out CSV as Excel *************
// this does not allow you to aggregate totals because PHPExcel cannot sort
// but, you could easily apply styling with PHPExcel
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
echo 'Example 1: Write out excel file with csv data (from input/BOM.csv.)<br>';
echo 'This method will allow for formatting, but not aggregating of quantities<br>';
try {
    $objWriter->save('output/BOM_from_CSV.xlsx');
}  catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
}



if (file_exists('output/BOM_from_CSV.xlsx')) {
   echo  'The file BOM_from_CSV.xlsx has been created in the output folder<br>';
} else {
    echo  'The file BOM_from_CSV.xlsx could not be written, open up permissions on the \'output\' folder<br>';
}


echo '<br>Example 2: Aggregating of quantity column (or any column)<br>';
ini_set('auto_detect_line_endings', true);
$file = fopen($inputFileName, 'r');
$headerRow['header'] = fgetcsv($file); // grab the first row as the header

$rows = array();
    if (isset($_GET['group_columns'][0]) and isset($_GET['total_column'])) {
        // there is a request to group the columns
        $totalColumn    = $_GET['total_column'];
        $groupColumns   = $_GET['group_columns'];
        while ( ($row = fgetcsv($file) ) !== FALSE ) {
            $aggregator = '';
            foreach ($groupColumns as $groupColumn) {
                $aggregator .= $row[$groupColumn] . '_';
            }
            if (isset($rows[$aggregator])) {
                $rows[$aggregator][$totalColumn] += $row[$totalColumn]; //add values from grouped rows
            } else {
                $rows[$aggregator] = $row;
                $rows[$aggregator][$totalColumn] = $row[$totalColumn];
            }
        }

        $rows = array_merge($headerRow, $rows); // add out header row back in
    } else {
        echo 'Send in a query parameter to generate an xlsx with totals for columns<br>';
        echo 'Provide group_columns parameters to identify which columns make a row a duplicate (provide indexes of column)<br>';
        echo 'Provide the index of the column to total as total_column<br>';
        echo 'Example query: http://localhost/csv2xl/?group_columns[0]=2&group_columns[1]=3&total_column=0<br>';
    }


    if (isset($rows['header'])) {
        // we have rows, go ahead and create the xlsx doc
        $doc = new PHPExcel();
        $i = 1;
        foreach ($rows as $row) {
            $doc->getActiveSheet()->fromArray($row, NULL, 'A'.$i);
            $i++;
        }
        $objWriter = new PHPExcel_Writer_Excel2007($doc);
        try {
            $objWriter->save('output/BOM_from_CSV_Totals.xlsx');
        }  catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }


        if (file_exists('output/BOM_from_CSV_Totals.xlsx')) {
            echo 'The file BOM_from_CSV_Totals.xlsx has been created in the output folder<br>';
        } else {
            echo 'The file BOM_from_CSV_Totals.xlsx could not be created, open up permissions on the \'output\' directory.<br>';
        }
    }



//var_dump($rows);

echo 'Script execution complete.</pre>';