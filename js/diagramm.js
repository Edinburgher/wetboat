$(document).ready(function () {

    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    var points = {};
    points['temperature_air'] = [];
    points['temperature_water'] = [];
    points['speed_wind'] = [];
    points['speed_boat'] = [];
    points['lat_boat'] = [];
    points['lon_boat'] = [];

    $.ajax({
        type: 'POST',
        url: 'php/getMeasurements.php',
        data: "",
        async: true,
        dataType: 'json',
        success: function (rows) {
            //rows is [{}]

            rows.forEach(function (elem) {
                var t = elem['time_measured'].split(/[- :]/);
                var time_measured = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5])).getTime();
                points['temperature_air'].push([
                    time_measured, parseFloat(elem['temperature_air'])
                ]);
                points['temperature_water'].push([
                    time_measured, parseFloat(elem['temperature_water'])
                ]);
                points['speed_wind'].push([
                    time_measured, parseFloat(elem['speed_wind'])
                ]);
                points['speed_boat'].push([
                    time_measured, parseFloat(elem['speed_boat'])
                ]);
                points['lat_boat'].push([
                    time_measured, parseFloat(elem['lat_boat'])
                ]);
                points['lon_boat'].push([
                    time_measured, parseFloat(elem['lon_boat'])
                ]);

            });

            // Create the chart
            $('#container').highcharts('StockChart', {
                chart: {
                    events: {
                        load: function () {
                            // set up the updating of the chart each second
                            var series = this.series;
                            var x;
                            var y = [];
                            var lastMeasurement = new Date(0);

                            getDelay(function (delayMS) {
                                setInterval(function () {
                                    //Measurement data
                                    $.ajax({
                                        type: 'POST',
                                        url: 'php/getNewestMeasurement.php',
                                        data: "",
                                        async: true,
                                        dataType: 'json',
                                        success: function (measurement) {
                                            //data is {}
                                            var t = measurement['time_measured'].split(/[- :]/);
                                            var x = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));

                                            if (x > lastMeasurement) {
                                                lastMeasurement = x;
                                                y[0] = parseFloat(measurement['temperature_air']);
                                                y[1] = parseFloat(measurement['temperature_water']);
                                                y[2] = parseFloat(measurement['speed_wind']);
                                                y[3] = parseFloat(measurement['speed_boat']);

                                                for (var i = 0; i < series.length - 1; i++) {
                                                    series[i].addPoint([x, y[i]], (i + 2 === series.length), true);
                                                }

                                                setNewestBoatMarker(x,measurement['lat_boat'], measurement['lon_boat']);

                                                
                                            }
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            console.log(errorThrown);
                                        }
                                    });

                                }, delayMS);
                            });
                        }
                    }
                },
                yAxis: [{ // Primary yAxis
                    id: 'axisTemp',
                    gridLineWidth: 1,
                    showEmpty: false,
                    labels: {
                        format: '{value}°C',
                        style: {
                            color: Highcharts.getOptions().colors[5]
                        }
                    },
                    title: {
                        text: 'Temperature',
                        style: {
                            color: Highcharts.getOptions().colors[5]
                        }
                    },
                    opposite: false

                }, { // Secondary yAxis
                    id: 'axisSpeed',
                    gridLineWidth: 1,
                    showEmpty: false,
                    title: {
                        text: 'Geschwindigkeit',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} km/h',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }

                }, { // Tertiary yAxis
                    id: 'axisPercent',
                    gridLineWidth: 0,
                    showEmpty: false,
                    title: {
                        text: 'Luftfeuchte',
                        style: {
                            "color": "blue",
                        }
                    },
                    labels: {
                        format: '{value} %',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }],
                tooltip: {
                    shared: true
                },
                rangeSelector: {
                    /*buttons: [{
                        count: 1,
                        type: 'minute',
                        text: '1M'
                    }, {
                        count: 5,
                        type: 'minute',
                        text: '5M'
                    }, {
                        type: 'all',
                        text: 'All'
                    }],*/
                    buttons: [{
                        type: 'all',
                        text: 'All'
                    }],
                    inputEnabled: false,
                    selected: 0
                },
                title: {
                    text: 'Live Messdaten'
                },
                legend: {
                    enabled: true,
                    maxHeight: 100
                },
                exporting: {
                    enabled: true
                },
                xAxis: {
                    type: 'datetime'
                },

                series: [{
                    name: 'Lufttemperatur',
                    data: points['temperature_air'],
                    yAxis: 'axisTemp',
                    color: Highcharts.getOptions().colors[5],
                    tooltip: {
                        valueSuffix: ' °C'
                    }
                }, {
                    name: 'Wassertemperatur',
                    data: points['temperature_water'],
                    yAxis: 'axisTemp',
                    color: Highcharts.getOptions().colors[0],
                    tooltip: {
                        valueSuffix: ' °C'
                    }
                }, {
                    name: 'Windgeschwindigkeit',
                    data: points['speed_wind'],
                    yAxis: 'axisSpeed',
                    color: Highcharts.getOptions().colors[4],
                    tooltip: {
                        valueSuffix: ' km/h'
                    }
                }, {
                    name: 'Bootsgeschwindigkeit',
                    data: points['speed_boat'],
                    yAxis: 'axisSpeed',
                    color: Highcharts.getOptions().colors[6],
                    tooltip: {
                        valueSuffix: ' km/h'
                    }
                }]
            });
        }
    });
});