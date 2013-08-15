<?php

include_once('lock.php');

include_once('plugins/gChart.php');
include_once('dbplugin.php');
include_once('date_handler.php');
include_once('plugins/html_table.class.php');

$crntYear = getYear();
$crntMonth = getMonth();

// for monthly report
$monthlyArray = generateMonthlyReportData($db, $crntYear, $crntMonth);

if (count($monthlyArray) >= 1) {
    $monthlyCatArray = array();
    $monthlySumArray = array();

    foreach ($monthlyArray as $val) {
        list($cat, $amt) = explode("|", $val);

        $monthlyCatArray[] = $cat;
        $monthlySumArray[] = $amt;

        $piChartM = new gPieChart();
        $piChartM->addDataSet(array($monthlySumArray[0], $monthlySumArray[1], $monthlySumArray[2], $monthlySumArray[3], $monthlySumArray[4]));
        $piChartM->setLabels(array($monthlyCatArray[0], $monthlyCatArray[1], $monthlyCatArray[2], $monthlyCatArray[3], $monthlyCatArray[4]));
        $piChartM->setColors(array("C50000", "1000C5", "14C500", "C5AE00", "AB00C5", "04B7B1", "B75504", "FF00EF", "00FFE6", "9EFF00", "FF5100", "0000FF", "CCOOOO", "C50000", "1000C5", "14C500", "C5AE00", "AB00C5", "04B7B1", "B75504", "FF00EF", "00FFE6", "9EFF00", "FF5100", "0000FF", "CCOOOO"));
    }
}
?>
<html>
<head>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
    <link type="text/css" href="scripts/jquery/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet"/>
    <script type="text/javascript" src="scripts/jquery/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="scripts/jquery/js/jquery-ui-1.10.3.custom.js"></script>
    <style type="text/css">
        table.demoTbl {
            border-collapse: collapse;
            border-spacing: 0;
        }

        #tblCap {
            font-weight: bold;
            font-size: 15px;
            margin: 1em auto .4em;
        }

        table.demoTbl .percentage {
            width: 150px;
        }

        table.demoTbl .category {
            width: 250px;
        }

        table.demoTbl .amount {
            width: 150px;
        }

        table.demoTbl td, table.demoTbl th {
            padding: 1px;
        }

        table.demoTbl th.first {
            text-align: center;
            padding: 3px;
            background-color: #CCCCCC;
            border: 2px solid #FFFFFF;
        }

        table.demoTbl td.per {
            text-align: center;
        }

        table.demoTbl td.cat {
            text-align: center;
        }

        table.demoTbl td.amt {
            text-align: right;
            padding-right: 10px;
        }

        table.demoTbl td.foot {
            text-align: center;
        }
    </style>
</head>
<body>
<h3>Monthly Expenses Report ( <?php echo $crntMonth . "-" . $crntYear ?> )</h3>
<img src="<?php
if (count($monthlyArray) >= 1) {
    print $piChartM->getUrl();
} ?>"/>
<br>
<?php

$getCatssql = "select c.category,sum(amount) as sums from expences e, category c where e.year='" . $crntYear . "' and e.month='" . $crntMonth . "' and e.category_id = c.id group by e.category_id order by sums desc";
$cat_result = mysqli_query($db, $getCatssql);
$rowcount = mysqli_num_rows($cat_result);

if ($rowcount >= 1) {

    // arguments: id, class, border,
    // can include associative array of optional additional attributes
    $tbl = new HTML_Table('', 'demoTbl', 0);

    $tbl->addCaption('Category Breakdown', 'cap', array('id' => 'tblCap'));

    $tbl->addColgroup();
    // span, class
    $tbl->addCol(0, 'percentage');
    $tbl->addCol(1, 'category');
    $tbl->addCol(2, 'amount');

    // thead
    $tbl->addTSection('thead');
    $tbl->addRow();
    // arguments: cell content, class, type (default is 'data' for td, pass 'header' for th)
    // can include associative array of optional additional attributes
    $tbl->addCell('Percentage', 'first', 'header');
    $tbl->addCell('Category', 'first', 'header');
    $tbl->addCell('Expences (Rs)', 'first', 'header');


    // tfoot
    $tbl->addTSection('tfoot');
    $tbl->addRow();
    // span all 3 columns
    $tbl->addCell('', 'foot', 'data', array('colspan' => 4));

    // tbody
    $tbl->addTSection('tbody');

    $getTotSum = "select total from summary_month where year='" . $crntYear . "' and month='" . $crntMonth . "'";
    $totalSum = mysqli_query($db, $getTotSum);
    $totRow = mysqli_fetch_array($totalSum);
    $total = $totRow['total'];

    while ($row = mysqli_fetch_array($cat_result)) {
        $category = $row['category'];
        $sum = number_format($row['sums'], 2);
        $percentage = number_format((str_replace(",", "", $sum) / $total) * 100, 2);

        $tbl->addRow();
        $tbl->addCell($percentage . '%', 'per');
        $tbl->addCell($category, 'cat');
        $tbl->addCell($sum, 'amt');
    }
    echo $tbl->display();
} else {
    echo "<b>No Data to Display</b>";
}
?>
</body>
</html>
