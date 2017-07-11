<!DOCTYPE html>
<html>
<header>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css"/>
</header>
<script src="js/xhr.js" type="text/javascript" charset="utf-8"></script>
<body>
    <div class="jumbotron">
        <h1 class="text-center">探针信息显示</h1>
    </div>
    <div>
        <table class="table text-center">
            <thead>
                <tr>
                    <td>mmac</td>
                    <td>rate</td>
                    <td>time</td>
                    <td>lat</td>
                    <td>lon</td>
                    <td>mac</td>
                    <td>rssi</td>
                    <td>range</td>
                    <td>ts</td>
                    <td>tmc</td>
                    <td>tc</td>
                    <td>ds</td>
                    <td>essid0</td>
                    <td>essid1</td>
                    <td>essid2</td>
                    <td>essid3</td>
                    <td>essid4</td>
                    <td>essid5</td>
                    <td>essid6</td>
                </tr>
            </thead>
            <tbody id = "tw-pin-info-table">
                <?php
                    function twGetType($var)
                    {
                        if (is_array($var)) return "array";
                        if (is_bool($var)) return "boolean";
                        if (is_float($var)) return "float";
                        if (is_int($var)) return "integer";
                        if (is_null($var)) return "NULL";
                        if (is_numeric($var)) return "numeric";
                        if (is_object($var)) return "object";
                        if (is_resource($var)) return "resource";
                        if (is_string($var)) return "string";
                        return "unknown type";
                    }
                    function twCheckAdd($va,$k){
                        if (array_key_exists($k, $va)) {
                            if ($va[$k]) {
                                echo "<td> $va[$k]</td>";
                            }
                            else{
                                echo "<td> None </td>";
                            }
                        }
                        else{
                            echo "<td> None </td>";
                        }
                    }
                    $con = mysqli_connect("localhost","root","cc1985");
                    if (!$con)
                    {
                        die('Could not connect: ' . mysqli_connect_error());
                    }
                    mysqli_select_db($con, "searchpin");

                    $sql ="select data from pininfo order by id desc limit 1"; 
                    $resultGetLastID = mysqli_query($con, $sql);
                    $info = mysqli_fetch_array($resultGetLastID, MYSQLI_ASSOC);
                    $dInfo = json_decode($info['data'],true);
                    if(empty($dInfo))
                    {
                        $lastID = '暂无';
                    }
                    else
                    {
                         foreach ($dInfo['data'] as $key => $value) {
                            echo "<tr>";
                                twCheckAdd($dInfo, "mmac");
                                twCheckAdd($dInfo, "rate");
                                twCheckAdd($dInfo, "time");
                                twCheckAdd($dInfo, "lat");
                                twCheckAdd($dInfo, "lon");
                                twCheckAdd($value, "mac");
                                twCheckAdd($value, "rssi");
                                twCheckAdd($value, "range");
                                twCheckAdd($value, "ts");
                                twCheckAdd($value, "tmc");
                                twCheckAdd($value, "tc");
                                twCheckAdd($value, "ds");
                                twCheckAdd($value, "essid0");
                                twCheckAdd($value, "essid1");
                                twCheckAdd($value, "essid2");
                                twCheckAdd($value, "essid3");
                                twCheckAdd($value, "essid4");
                                twCheckAdd($value, "essid5");
                                twCheckAdd($value, "essid6");
                            echo "</tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        var stampn = parseInt(new Date().getTime()/1000);
        XHR.poll(5, '', null, function (argument) {
            XHR.get('/dataGet.php', "", function(x, info) {
                    var dataInfo = eval('(' + x.responseText + ')');
                    refreshInfo(dataInfo);
                });
        });

        var iIndex = 0;

        function refreshInfo(data) {
            var tableBody = document.getElementById("tw-pin-info-table");
            var i = tableBody.childNodes.length - 1;
            for(; i >= 0; i--) { 
                tableBody.removeChild(tableBody.childNodes[i]); 
            }
            var infoData = eval('(' + data.data + ')')

            var dataId = infoData.dataId;
            var mmac = infoData.mmac;
            var rate = infoData.rate;
            var time = infoData.time;
            var lat = infoData.lat;
            var lon = infoData.lon;

            var count = infoData.data.length;
            for(var i = 0; i < count; i++) {
                var useData = infoData.data[i];
                var html = document.createElement("tr");
                html.appendChild(create_td_element(( mmac ? mmac : "None" )));
                html.appendChild(create_td_element(( rate ? rate : "None" )));
                html.appendChild(create_td_element(( time ? time : "None" )));
                html.appendChild(create_td_element(( lat === "" ? "None" : lat )));
                html.appendChild(create_td_element(( lon === "" ? "None" + (iIndex++).toString() : (lon + iIndex++).toString()  )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("mac") ? useData.mac : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("rssi") ? useData.rssi : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("range") ? useData.range : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("ts") ? useData.ts : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("tmc") ? useData.tmc : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("tc") ? useData.tc : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("ds") ? useData.ds : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("essid0") ? useData.essid0 : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("essid1") ? useData.essid1 : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("essid2") ? useData.essid2 : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("essid3") ? useData.essid3 : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("essid4") ? useData.essid4 : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("essid5") ? useData.essid5 : "None" )));
                html.appendChild(create_td_element(( useData.hasOwnProperty("essid6") ? useData.essid6 : "None" )));

                tableBody.appendChild(html);
            }
            // $(".system_info tr:not(:first)").empty();
        }
        function create_td_element(textShow){
            var td = document.createElement("td");
            td.appendChild(document.createTextNode(textShow));
            return td;
        }
    </script>
</body>
</html>
