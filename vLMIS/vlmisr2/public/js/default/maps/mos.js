
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

    vLMIS = new OpenLayers.Layer.Vector("Months of Stock", {
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
    getLegend('1', 'Months of Stock');   
    handler = setInterval(readData, 2000);
});

function readData() {
    if (province.features.length == "9" && district.features.length == "147") {
        getData();
        clearInterval(handler);
    }
}

$("#submit").click(function() {getData();});

function getData() {
    clearData();
    var year     = $("#year").val();
    var month    = $('#slider').slider("option", "value");
    var region   = $("#province").val();
    var product  = $("#product").val();
    var level    = $("#level").val();
    product_name = $("#product option:selected").text();
    
    mapTitle();
    onFeatureUnselect();
   
    $.ajax({
        url: appName + "/api/geo/get-mos-map-data",
        type: "GET",
        data: {
            year: year,
            month: month,
            province: region,
            product: product,
            level: level
        },
        dataType: "json",
        success: callback,
        error: function(response) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            $("#submit").attr("disabled", false);
            $("#slider").slider("enable");
            return;
        }
    });

    function callback(response) {
        data = response;
        drawFeature();
    }
}

function drawFeature() {
    if (vLMIS.features.length > 0) {
        vLMIS.removeAllFeatures();
    }
    FilterData();
    if (data.length <= 0) {
            alert("No Data Available");
            $("#loader").css("display", "none");
            $("#submit").attr("disabled", false);
            $("#slider").slider("enable");
    }
    data.sort(SortByID);
    for (var i = 0; i < data.length; i++) {
        chkeArray(data[i].district_id, Number(data[i].mos));
    }
    drawGrid();
    districtCountGraph();
    $("#submit").attr("disabled", false);
    $("#slider").slider("enable");
}

function chkeArray(district_id, mos) {
    for (var i = 0; i < district.features.length; i++) {
        if (district_id == district.features[i].attributes.district_id) {
            vLMISdistrictLayer(district.features[i].geometry, district.features[i].attributes.province_id,district.features[i].attributes.province_name, product_name, district_id, district.features[i].attributes.district_name, mos);
            break;
        }
    }
}

function vLMISdistrictLayer(wkt, province_id, province, product, district_id, district_name, value) {
    feature = new OpenLayers.Feature.Vector(wkt);


    if (value == classesArray[0].start_value && value == classesArray[0].end_value) {
        color = classesArray[0].color_code;
        NoData = Number(NoData) + 1;
        status = classesArray[0].description;
    }
    if (value > classesArray[1].start_value && value <= classesArray[1].end_value) {
        color = classesArray[1].color_code;
        StockOut = Number(StockOut) + 1;
        status = classesArray[1].description;
    }
    if (value >= classesArray[2].start_value && value <= classesArray[2].end_value) {
        color = classesArray[2].color_code;
        UnderStock = Number(UnderStock) + 1;
        status = classesArray[2].description;
    }
    if (value >= classesArray[3].start_value && value <= classesArray[3].end_value) {
        color = classesArray[3].color_code;
        Satisfactory = Number(Satisfactory) + 1;
        status = classesArray[3].description;
    }
    if (value >= classesArray[4].start_value) {
        color = classesArray[4].color_code;
        OverStock = Number(OverStock) + 1;
        status = classesArray[4].description;
    }

    feature.attributes = {
        district_id: district_id,
        province_id: province_id,
        district: district_name,
        province: province,
        product: product,
        status : status,
        value: value,
        color: color
    };
    vLMIS.addFeatures(feature);

    $("#loader").css("display", "none");
}


function onFeatureSelect(e) {
    $("#prov").html(e.feature.attributes['province']);
    $("#district").html(e.feature.attributes['district']);
    $("#stakeholder").html(e.feature.attributes['StkHolder']);
    $("#prod").html(e.feature.attributes['product']);
    $("#mos").html(e.feature.attributes['value']);
}

function onFeatureUnselect(e) {
    $("#prov").html("");
    $("#district").html("");
    $("#stakeholder").html("");
    $("#prod").html("");
    $("#mos").html("");
}


function clearData(){
    $("#loader").show();
    $('.radio-button').prop('checked', false);
    $("#slider").slider("disable");
    $("#submit").attr("disabled", true);
    $("#graph").html("Click any district for previous Stock Trend");
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    $("#mapTitle").html("");
    pieArray.length = 0;
    NoData = '0';
    StockOut = '0';
    UnderStock = '0';
    Satisfactory = '0';
    OverStock = '0';
}


function mapTitle() {
    prov_name = $("#province option:selected").text();
    if(prov_name == "Select"){prov_name = "Pakistan"};
    year_name = $("#year option:selected").text();
    month_value = ($('#slider').slider("option", "value")) - 1;
    var month_name = monthNames[month_value];
    month_year = month_name + " " + year_name;
    level_name = $("#level option:selected").text();
    download = product_name+"->"+month_year;
    $("#mapTitle").html("<font color='green' size='4'><b>Months of Stock   (" + month_year + ")</b></font> <br/> " + product_name + " at " + level_name + " level");

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


function getLegend(value, title) {
    $.ajax({
        url: appName + "/api/geo/get-color-classes",
        type: "GET",
        data: "id=" + value,
        dataType: "json",
        success: callback
    });

    function callback(response) {
        classesArray = response;

       var classes = parseInt(classesArray.length) - 1;
       $('#legendDiv').append( '<table id="legend">' );
       
       $('#legendDiv').append("<tr><td colspan='3'><font size='2' color='green'><b>" + title + "</b></font></td></tr>");
       for (var i = 1 ; i <= classes; i++) {
          $('#legendDiv').append("<tr><td align='right' valign='middle' class='hide_td'><input name='color' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\""+classesArray[i].description +"\")'/></td><td align='right' valign='middle' style='width:35px'><div style='cursor:pointer;width:30px;height:18px;background-color:" + classesArray[i].color_code + "'></div></td><td align='left' valign='middle' style='padding-left:3px'>"+classesArray[i].description+"</td></tr>"); 
       }
       $('#legendDiv').append("<br/>");
       
       for (var i = 0 ; i < 1; i++) {
          $('#legendDiv').append("<tr><td align='right' valign='middle' class='hide_td'><input name='color' class='radio-button' type='radio' onclick='getColorName(\"" + classesArray[i].color_code + "\",\""+classesArray[i].description +"\")'/></td><td align='right' valign='middle' style='width:35px'><div style='cursor:pointer;width:30px;height:18px;background-color:" + classesArray[i].color_code + "'></div></td><td align='left' valign='middle' style='padding-left:3px'>"+classesArray[i].description+"</td></tr>"); 
       }
       $('#legendDiv').append("<tr><td colspan='3' align='right'><a class='undo' onclick='getFullColor()'>Reset</a><td></tr>");
       $('#legendDiv').append( '</table>' );

       $("#legendDiv").css("display", "block");

        var defination = document.getElementById('mosRanges');
        for (var i = classes; i >= 1; i--) {

            var row = defination.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.align = "left";    
            cell2.align = "center";
            cell1.innerHTML = classesArray[i].description;
            cell2.innerHTML = classesArray[i].interval;

        }   
        $("#mosDefination").css("display", "block");
    }
}


function SortByID(x, y) {return x.mos - y.mos;}

function drawGrid() {
    $("#attributeGrid").html("");
    $("#districtRanking").html("");
    dataDownload.length = 0;
    jsonData.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th>Product</th><th>MOS</th></thead>";
    for (var i = 0; i < features.length; i++) {
        table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.product + "</td><td align='right'>" + features[i].attributes.value + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
        jsonData.push({
            label: features[i].attributes.district,
            value: features[i].attributes.value,
            color: features[i].attributes.color
        });
        dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            product: features[i].attributes.product,
            Status:features[i].attributes.status,
            MOS: features[i].attributes.value
        });
    }
    table += "</table>";
    $("#attributeGrid").append(table);
    maximum = vLMIS.features.length;
    var pageTitle = $(".page-title").html();
    var title = pageTitle.split("Map");
    districtRanking(jsonData,"- "+title[0]);
}


function districtCountGraph() {

    var ND = CalculatePercent(NoData, maximum);
    var SO = CalculatePercent(StockOut, maximum);
    var US = CalculatePercent(UnderStock, maximum);
    var SAT = CalculatePercent(Satisfactory, maximum);
    var OS = CalculatePercent(OverStock, maximum);

    pieArray.push({
        label: 'No Data',
        value: ND,
        color: classesArray[0].color_code
    });
    pieArray.push({
        label: 'Stock Out',
        value: SO,
        color: classesArray[1].color_code
    });
    pieArray.push({
        label: 'Under Stock',
        value: US,
        color: classesArray[2].color_code
    });
    pieArray.push({
        label: 'Satisfactory',
        value: SAT,
        color: classesArray[3].color_code
    });
    pieArray.push({
        label: 'Over Stock',
        value: OS,
        color: classesArray[4].color_code
    });

    var revenueChart = new FusionCharts({
        type: 'column3D',
        renderAt: 'chart-container',
        width: '100%',
        height: '260px',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name+" - MOS Status",
                "subcaption":download,
                "showLabels": '1',
                "showlegend":'1',
                "slantLabels": '1',
                "showValues": '1',
                "labelDisplay":'Rotate',
                "numberSuffix": '%',
                "exportEnabled": '1',
                "theme": 'fint'            
            },
            "data": pieArray
        }
    });
    revenueChart.render("pie");
}


function districtRanking(records,title) {

    records.sort(SortByRankingID);
    
    if (records.length > 51) {
        width = '200%';
    } 
    else {
        width = '100%';
    }

    var revenueChart = new FusionCharts({
        type: 'column2d',
        renderAt: 'chart-container',
        width: width,
        height: '100%',
        dataFormat: 'json',
        dataSource: {
            "chart": {
                "caption": prov_name+" - District wise Stock Ranking "+ title,
                "subcaption":download,
                "slantLabels": '1',            
                "showValues": '1',
                "rotateValues": '1',
                "placeValuesInside": '0',
                "adjustDiv":'0',
                "numDivLines":'3',
                "xAxisName": "",
                "yAxisName": "No.of Months",
                "exportEnabled": "1",
                "theme": "fint"
            },
            "styles":{
                "definition":[{
                    "name":"myValuesFont",
                    "type":"font",
                    "size":"10",
                    "color":"666666",
                    "bold":"1"
                  }
                ],
                "application":[{
                    "toobject":"DataValues",
                    "styles":"myValuesFont"
                  }
                ]
              },
            "data": records
        }
    });
    revenueChart.render("districtRanking");
}


function gridFilter(color) {
    $("#attributeGrid").html("");
    dataDownload.length = 0;
    var features = vLMIS.features;
    table = "<table class='table table-condensed table-hover'>";
    table += "<thead><th>Province</th><th>District</th><th>Product</th><th>MOS</th><th></th></thead>";
    for (var i = 0; i < features.length; i++) {
        if (features[i].attributes.color == color) {
            table += "<tr><td>" + features[i].attributes.province + "</td><td>" + features[i].attributes.district + "</td><td>" + features[i].attributes.product + "</td><td align='right'>" + features[i].attributes.value + "</td><td><div style='width:30px;height:18px;background-color:" + features[i].attributes.color + "'></div></td></tr>";
            dataDownload.push({
            province: features[i].attributes.province,
            district_name: features[i].attributes.district,
            product: features[i].attributes.product,
            Status:features[i].attributes.status,
            MOS: features[i].attributes.value
        });
        }
    }
    table += "</table>";
    $("#attributeGrid").append(table);
}

