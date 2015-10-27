function FilterData() {

    var prov = $("#province option:selected").text();

    var features = province.features;
    var districtfeatures = district.features;

    for (var i = 0; i < features.length; i++) {
        features[i].style = '';
    }
    province.redraw();

    for (var i = 0; i < districtfeatures.length; i++) {
        districtfeatures[i].style = '';
    }
    district.redraw();

    if (prov == "Select") {
        map.setOptions({
            maxExtent: restricted
        });
        map.setOptions({
            restrictedExtent: restricted
        });
        map.events.register("zoomend", map, zoomChanged);
        map.events.register("zoomend", map, zoomRestrict);
        map.events.register("move", map, UpdateExtent);
        downloadExtent = bounds;
        map.zoomToExtent(bounds);
    } else {

        map.setOptions({
            maxExtent: null
        });
        map.setOptions({
            restrictedExtent: null
        });
        map.events.register("zoomend", map, zoomChanged);
        map.events.register("zoomend", map, zoomRestrict);
        map.events.register("move", map, UpdateExtent);

        for (var i = 0; i < features.length; i++) {
            if (features[i].attributes.province_name != prov) {
                features[i].style = {
                    display: 'none'
                };
            } else {

                downloadExtent = features[i].geometry.getBounds();
                map.zoomToExtent(features[i].geometry.getBounds());
            }
        }


        if (features[3].attributes.province_name == prov) {
            map.events.remove("zoomend", map, zoomChanged);
            map.events.remove("zoomend", map, zoomRestrict);
            map.events.remove("move", map, UpdateExtent);

            var isb = new OpenLayers.Bounds(72.6601, 33.472, 73.4703, 33.9187);
            isb.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
            downloadExtent = isb;
            map.zoomToExtent(isb);
        }

        province.redraw();

        for (var i = 0; i < districtfeatures.length; i++) {
            if (districtfeatures[i].attributes.province_name != prov) {
                districtfeatures[i].style = {
                    display: 'none'
                };
            }
        }
        district.redraw();
    }
}



///////////////////////////////////////////////////////////////////////////////////////////




function FilterTwoFrame() {

    var prov = $("#province option:selected").text();

    var features = province.features;
    var districtfeatures = district.features;

    var features2 = province2.features;
    var districtfeatures2 = district2.features;

    for (var i = 0; i < features.length; i++) {
        features[i].style = '';
    }
    province.redraw();

    for (var i = 0; i < districtfeatures.length; i++) {
        districtfeatures[i].style = '';
    }
    district.redraw();



    for (var i = 0; i < features2.length; i++) {
        features2[i].style = '';
    }
    province2.redraw();

    for (var i = 0; i < districtfeatures2.length; i++) {
        districtfeatures2[i].style = '';
    }
    district2.redraw();




    if (prov == "Select") {

        downloadExtent = bounds;
        map.zoomToExtent(bounds);
        map2.zoomToExtent(bounds);
    } else {



        for (var i = 0; i < features.length; i++) {
            if (features[i].attributes.province_name != prov) {
                features[i].style = {
                    display: 'none'
                };

            } else {
                downloadExtent = features[i].geometry.getBounds();
                map.zoomToExtent(features[i].geometry.getBounds());
            }
        }

        for (var i = 0; i < features2.length; i++) {
            if (features2[i].attributes.province_name != prov) {
                features2[i].style = {
                    display: 'none'
                };

            } else {

                downloadExtent = features2[i].geometry.getBounds();
                map2.zoomToExtent(features2[i].geometry.getBounds());
            }
        }



        if (features[3].attributes.province_name == prov) {
            features[3].style = {
                display: 'none'
            };

            var isb = new OpenLayers.Bounds(72.6601, 33.472, 73.4703, 33.9187);
            isb.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
            downloadExtent = isb;
            map.zoomToExtent(isb);
        }


        if (features2[3].attributes.province_name == prov) {

            features2[3].style = {
                display: 'none'
            };

            var isb = new OpenLayers.Bounds(72.6601, 33.472, 73.4703, 33.9187);
            isb.transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
            downloadExtent = isb;
            map2.zoomToExtent(isb);
        }


        province.redraw();
        province2.redraw();

        for (var i = 0; i < districtfeatures.length; i++) {
            if (districtfeatures[i].attributes.province_name != prov) {
                districtfeatures[i].style = {
                    display: 'none'
                };
            }
        }
        district.redraw();

        for (var i = 0; i < districtfeatures2.length; i++) {
            if (districtfeatures2[i].attributes.province_name != prov) {
                districtfeatures2[i].style = {
                    display: 'none'
                };
            }
        }
        district2.redraw();
    }

}


function zoomChanged() {
    var zoom = map.getZoom();
    if (zoom >= 1) {
        province.styleMap = province_style;
        district.styleMap = district_style_label;
        province.redraw();
        district.redraw();
    }
    if (zoom == "0") {
        province.styleMap = province_style_label;
        district.styleMap = district_style;
        province.redraw()
        district.redraw();
    }
}

function zoomLabel() {
    var zoom = map.getZoom();

    if (zoom >= 1) {
        province.styleMap = province_style_label;
        province2.styleMap = province_style_label;
        district.styleMap = district_style_label;
        district2.styleMap = district_style_label;
        province.redraw();
        province2.redraw();
        district.redraw();
        district2.redraw();
    }

    if (zoom == "0") {
        province.styleMap = province_style;
        province2.styleMap = province_style;
        district.styleMap = district_style;
        district2.styleMap = district_style;
        province.redraw();
        province2.redraw();
        district.redraw();
        district2.redraw();
    }

}

function zoomLabel2() {
    var zoom = map2.getZoom();

    if (zoom >= 1) {
        province.styleMap = province_style;
        province2.styleMap = province_style;
        district.styleMap = district_style_label;
        district2.styleMap = district_style_label;
        province.redraw();
        province2.redraw();
        district.redraw();
        district2.redraw();
    }
    if (zoom == "0") {
        province.styleMap = province_style_label;
        province2.styleMap = province_style_label;
        district.styleMap = district_style;
        district2.styleMap = district_style;
        province.redraw();
        province2.redraw();
        district.redraw();
        district2.redraw();
    }

}

function UpdateExtent() {
    ext = map.getExtent();
}

function zoomRestrict() {
    var x = map.getZoom();
    if (x == 3) {
        ext = map.getExtent();
    }
    if (x >= 3) {
        map.zoomToExtent(ext);
    }
}

 $(function() {
     
     var date = new Date();
     var d = date.getDate();
     var day = (d < 10) ? '0' + d : d;
     
     if (day > 10) {  
             date.SubtractMonth(1);
             mon = date.getMonth() + 1 ;
     }else{
            date.SubtractMonth(2);
            mon = date.getMonth() + 1;
     }
     
     $("#slider").slider({
             value: mon,
             min: 1,
             max: 12,
             step: 1,
             change: function(event, ui) {
                 getData();
             }
         })
         .each(function() {

             // Get the options for this slider
             var opt = $(this).data().uiSlider.options;

             // Get the number of possible values
             var vals = opt.max - opt.min;

             // Space out values
             for (var i = 0; i <= vals; i++) {
                 var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                 var el = $('<label>' + monthNames[i] + '</label>').css('left', (i / vals * 100) + '%');

                 $("#slider").append(el);
             }

         });
         
    });
    
    
 
 function SortByRankingID(x, y) {return y.value - x.value;}

 function CalculatePercent(value, total) {return Math.round(100 * (value / total));}
 
 Date.prototype.SubtractMonth = function(numberOfMonths) {
            var d = this;
            d.setMonth(d.getMonth() - numberOfMonths);
            d.setDate(1);
            return d;
 }