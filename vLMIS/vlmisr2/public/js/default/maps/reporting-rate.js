

$(window).load(function() {

   map = new OpenLayers.Map('map', {
        projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326"),
        maxExtent: restricted,
        restrictedExtent: restricted,
        maxResolution: "auto",
        allOverlays: true,
        controls: [
            new OpenLayers.Control.Navigation({
                'zoomWheelEnabled': false
            }),
            new OpenLayers.Control.MousePosition({
                prefix: 'coordinates: ',
                numDigits: 2,
                separator: ' | '
            }),
            new OpenLayers.Control.Zoom({
                zoomInId: "customZoomIn",
                zoomOutId: "customZoomOut"
            }),
            new OpenLayers.Control.ScaleLine()
        ]
    });

    province = new OpenLayers.Layer.Vector(
        "Province", {
            protocol: new OpenLayers.Protocol.HTTP({
                url: appName + "/js/province.geojson",
                format: new OpenLayers.Format.GeoJSON({
                    internalProjection: new OpenLayers.Projection("EPSG:3857"),
                    externalProjection: new OpenLayers.Projection("EPSG:3857")
                })
            }),
            strategies: [new OpenLayers.Strategy.Fixed()],
            styleMap: province_style_label,
            isBaseLayer: true
        });

    district = new OpenLayers.Layer.Vector(
        "District", {
            protocol: new OpenLayers.Protocol.HTTP({
                url: appName + "/js/district.geojson",
                format: new OpenLayers.Format.GeoJSON({
                    internalProjection: new OpenLayers.Projection("EPSG:3857"),
                    externalProjection: new OpenLayers.Projection("EPSG:3857")
                })
            }),
            strategies: [new OpenLayers.Strategy.Fixed()],
            styleMap: district_style
        });

    vLMIS = new OpenLayers.Layer.Vector("Reporting Rate", {
        styleMap: vlMIS_style
    });
    map.addLayers([province, district, vLMIS]);
    district.setZIndex(900);
    province.setZIndex(1001);

    selectfeature = new OpenLayers.Control.SelectFeature([vLMIS]);
    map.addControl(selectfeature);
    selectfeature.activate();
    selectfeature.handlers.feature.stopDown = false;

    vLMIS.events.on({
        "featureselected": onFeatureSelect,
        "featureunselected": onFeatureUnselect
    });
    map.zoomToExtent(bounds);

    getLegend('4', 'Reporting Rate');
    handler = setInterval(readData, 2000);
});

function readData() {
    if (province.features.length == "9" && district.features.length == "147") {
        getData();
        clearInterval(handler);
    }
}

$("#submit").click(function() {
    getData();
});

var year,month,region;

function getData() {
   
    clearData();
    
    year = $("#year").val();
    month = $('#slider').slider("option", "value");
    region = $("#province").val();
    mapTitle();
    onFeatureUnselect();

    $.ajax({
        url: appName + "/api/geo/get-reporting-rate",
        type: "GET",
        data: {
            year: year,
            month: month,
            province: region
        },
        dataType: "json",
        success: callback,
        error: function(response) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            $("#submit").attr("disabled", false);
            $("#slider").slider("enable");
        }
    });

    function callback(response) {
        var data = [];
        data = response;
        FilterData();

        if (vLMIS.features.length > 0) {
            vLMIS.removeAllFeatures();
        }
        if (data.length <= 0){
            alert("No Data Available");
            $("#loader").css("display", "none");
            $("#submit").attr("disabled", false);
            $("#slider").slider("enable");
        }
        data.sort(SortByID);
        for (var i = 0; i < data.length; i++) {
            chkeArray(data[i].district_id, data[i].district_name, data[i].reported, data[i].total_warehouse, Number(data[i].reporting_rate));
        }
        drawGrid();
        districtCountGraph();
        $("#submit").attr("disabled", false);
        $("#slider").slider("enable");
    }
}

function chkeArray(district_id, district_name, reported, total_warehouse, reporting_rate) {
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {
            vLMISLayer(district.features[i].geometry, district.features[i].attributes.province_name, district_id, district.features[i].attributes.district_name, reported, total_warehouse, reporting_rate);
            break;
        }
    }
}

function vLMISLayer(wkt, province,district_id, district_name, reported, total_warehouse, reporting_rate) {
    feature = new OpenLayers.Feature.Vector(wkt);

    if (reporting_rate == classesArray[0].start_value && reporting_rate == classesArray[0].end_value) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;
    }
    if (reporting_rate > classesArray[1].start_value && reporting_rate <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
        class1 = Number(class1) + 1;
        status = classesArray[1].description;
    }
    if (reporting_rate > classesArray[2].start_value && reporting_rate <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
        class2 = Number(class2) + 1;
        status = classesArray[2].description;
    }
    if (reporting_rate > classesArray[3].start_value && reporting_rate <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
        class3 = Number(class3) + 1;
        status = classesArray[3].description;
    }
    if (reporting_rate > classesArray[4].start_value && reporting_rate <= classesArray[4].end_value) {
        color = classesArray[4].color_code;
        class4 = Number(class4) + 1;
        status = classesArray[4].description;
    }
    if (reporting_rate > classesArray[5].start_value && reporting_rate <= classesArray[5].end_value) {
        color = classesArray[5].color_code;
        class5 = Number(class5) + 1;
        status = classesArray[5].description;
    }
    
    feature.attributes = {
        district_id: district_id,
        district: district_name,
        province: province,
        reported: reported,
        total_warehouse: total_warehouse,
        reporting_rate: reporting_rate,
        status : status,
        color: color
    };
    vLMIS.addFeatures(feature);
    $("#loader").css("display", "none");
}


function onFeatureSelect(e) {
    $("#prov").html(e.feature.attributes['province']);
    $("#district").html(e.feature.attributes['district']);
    $("#total_ucs").html(e.feature.attributes['total_warehouse']);
    $("#reported_ucs").html(e.feature.attributes['reported']);
    $("#non_reported_ucs").html((e.feature.attributes['total_warehouse'] - e.feature.attributes['reported']));
    $("#reporting_rate").html(e.feature.attributes['reporting_rate']);
    lastMonthsStats(e.feature.attributes['district_id'], e.feature.attributes['district']);
}

function onFeatureUnselect(e) {
    $("#prov").html("");
    $("#district").html("");
    $("#total_ucs").html("");
    $("#reported_ucs").html("");
    $("#non_reported_ucs").html("");
    $("#reporting_rate").html("");
}

function mapTitle() {

    prov_name = $("#province option:selected").text();
    if(prov_name == "Select"){prov_name = "Pakistan"};
    year_name = $("#year option:selected").text();
    month_value = ($('#slider').slider("option", "value")) - 1;
    var month_name = monthNames[month_value];
    month_year = month_name + " " + year_name;
    download = month_year;
    
    $("#mapTitle").html("<font color='green' size='4'><b> Reporting Rate (" + month_year + ")</b></font> <br/> By UC");

    var date = new Date();
    var d = date.getDate();
    var day = (d < 10) ? '0' + d : d;
    var m = date.getMonth() + 1;
    var month = (m < 10) ? '0' + m : m;
    var yy = date.getYear();
    var year = (yy < 1000) ? yy + 1900 : yy;

    var printdate = "Printed Date: " + day + "/" + month + "/" + year;
    $("#printedDate").html("<b>" + printdate + "</b>");

}

function clearData(){
    $("#loader").show();
    $("#mapTitle").html("");
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    $("#graph").html("<p align='center'>Click any district for previous Reporting Rate Trend</p>");
    $("#slider").slider("disable");
    $("#submit").attr("disabled", true);
    $('.radio-button').prop('checked', false);
    pieArray.length = 0;
    NoData = '0';
    DataProblem = '0';
    class1 = '0';
    class2 = '0';
    class3 = '0';
    class4 = '0';
    class5 = '0';
}

function SortByID(x, y) {return x.reporting_rate - y.reporting_rate;}

function drawGrid(){
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th align='center'>Total UCs</th><th align='center'>Reported UCs</th><th align='center'>Reporting Rate(%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td align='center'>" + features[i].attributes.total_warehouse + "</td><td align='center'>" + features[i].attributes.reported + "</td><td align='center'>" + features[i].attributes.reporting_rate + "</td><td align='left'><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.district,
            value: features[i].attributes.reporting_rate,
            color: features[i].attributes.color
        });
        dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            total_UCs: features[i].attributes.total_warehouse,
            reported_UCs: features[i].attributes.reported,
            Status:features[i].attributes.status,
            reporting_rate: features[i].attributes.reporting_rate
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = vLMIS.features.length;
    districtRanking(jsonData,"");
}


function districtRanking(Data,title) {

   Data.sort(SortByRankingID);
   if (Data.length > 52) {
        width = '480%';
    } 
    else {
        width = '180%';
    }

    var revenueChart = new FusionCharts({
        type: 'column2D',
        renderAt: 'chart-container',
        width: width,
        height: '100%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name+" - District Wise Reporting Rate Ranking "+ title,
                "subcaption": download,
                "showLabels": "1",
                "slantLabels": '1',
                "enableLink": '0',
                "showValues": '1',
                "rotateValues": '1',
                "placeValuesInside": '1',
                "xAxisName": "",
                "yAxisName": "Reporting Rate",
                "numberSuffix": "%",
                "adjustDiv":'0',
                "numDivLines":'3',
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": Data
        }
    });
    revenueChart.render("districtRanking");
}


function gridFilter(color){
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th>Total UCs</th><th>Reported UCs</th><th>Reporting Rate (%)</th></thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
            table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td align='right'>" + features[i].attributes.total_warehouse + "</td><td align='right'>" + features[i].attributes.reported + "</td><td align='right'>" + features[i].attributes.reporting_rate + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
            dataDownload.push({
                province: features[i].attributes.province,
                district_name: features[i].attributes.district,
                total_UCs: features[i].attributes.total_warehouse,
                reported_UCs: features[i].attributes.reported,
                Status:features[i].attributes.status,
                reporting_rate: features[i].attributes.reporting_rate
            });
        }
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}

function districtCountGraph() {

    var ND = CalculatePercent(NoData, maximum);
    var cls1 = CalculatePercent(class1, maximum);
    var cls2 = CalculatePercent(class2, maximum);
    var cls3 = CalculatePercent(class3, maximum);
    var cls4 = CalculatePercent(class4, maximum);
    var cls5 = CalculatePercent(class5, maximum);

    pieArray.push({
        label: classesArray[0].description,
        value: ND,
        color: classesArray[0].color_code
    });
    pieArray.push({
        label: classesArray[1].description,
        value: cls1,
        color: classesArray[1].color_code
    });
    pieArray.push({
        label: classesArray[2].description,
        value: cls2,
        color: classesArray[2].color_code
    });
    pieArray.push({
        label: classesArray[3].description,
        value: cls3,
        color: classesArray[3].color_code
    });
    pieArray.push({
        label: classesArray[4].description,
        value: cls4,
        color: classesArray[4].color_code
    });
    pieArray.push({
        label: classesArray[5].description,
        value: cls5,
        color: classesArray[5].color_code
    });


    var revenueChart = new FusionCharts({
        type: 'column3D',
        renderAt: 'chart-container',
        width: '100%',
        height: '255px',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name+" - Reporting Rate Status",
                "subcaption":download,
                "showLabels": "1",
                "showlegend":"1",
                "slantLabels": '1',
                "labelDisplay":'Rotate',
                "enableLink": '1',
                "showValues": '1',
                "xAxisName": "",
                "numberSuffix": "%",
                "exportEnabled": "1",
                "theme": "fint"
            },
            "data": pieArray
        }
    });
    revenueChart.render("pie");
}


function lastMonthsStats(district_id, district_name) {
   
    $("#graph").html("");
    $("#graph").html("<img src='"+appName+"/images/ajax-loader.gif' style='display:block;width:100px;height:100px;margin-left: auto;margin-right: auto;'>");
    $.ajax({
        url: appName + "/api/geo/get-reporting-rate-trend",
        type: "GET",
        data: {
            year: year,
            month: month,
            province: region,
            district:district_id
        },
        dataType: "JSON",
        error: function(response) {
             $("#graph").html("");
             $("#graph").html("No Record Found");
        },
        success: callback
    });

    function callback(response) {

        var chart = [];
        chart = response;
        minMaxArray.length = 0;
        $("#graph").html("");
        
        for (var i = 0; i < chart.length; i++) {
            minMaxArray.push(Number(chart[i].value));
        }
                 
        var revenueChart = new FusionCharts({
            type: 'line',
            width: '100%',
            height: '190px',
            dataFormat: 'json',
            dataSource: {
                "chart": {
                    "caption": district_name+" - Previous Reporting Trend",
                    "xAxisName": "",
                    "exportEnabled": "1",
                    "enableLink": '1',
                    "showValues": '1',
                    "showLabels": "1",
                    "numberSuffix": "%",
                    "formatnumberscale": "1",
                    "showYAxisValues":'1',
                    "theme": "fint"
                },
                "data": chart
            }
        });
        revenueChart.render("graph");
        
    }
}