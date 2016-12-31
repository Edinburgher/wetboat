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
        success: function wtf(rows) {
            //rows is [{}]
            // console.log(rows);
            rows.forEach(function wtf2(elem) {
                points['temperature_air'].push([
                    Date.parse(elem['time_measured'] + ' UTC'), parseFloat(elem['temperature_air'])
                ]);

                points['temperature_water'].push([
                    Date.parse(elem['time_measured'] + ' UTC'), parseFloat(elem['temperature_water'])
                ]);
                points['speed_wind'].push([
                    Date.parse(elem['time_measured'] + ' UTC'), parseFloat(elem['speed_wind'])
                ]);
                points['speed_boat'].push([
                    Date.parse(elem['time_measured'] + ' UTC'), parseFloat(elem['speed_boat'])
                ]);
                points['lat_boat'].push([
                    Date.parse(elem['time_measured'] + ' UTC'), parseFloat(elem['lat_boat'])
                ]);
                points['lon_boat'].push([
                    Date.parse(elem['time_measured'] + ' UTC'), parseFloat(elem['lon_boat'])
                ]);

            });
            //console.log(rows[19]['time_measured']);
            //console.log(points);

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
                                    //x = (new Date()).getTime(); // current time
                                    /*console.log(x);
                                    y[0] = Math.round(Math.random() * 10 * 3) / 10 + 20;
                                    y[1] = Math.round(Math.random() * 10 * 3) / 10 + 16;
                                    y[2] = Math.round(Math.random() * 10 * 1) / 10 + 6;
                                    y[3] = Math.round(Math.random() * 10 * 3) / 10 + 5;*/

                                    //Measurement data


                                    $.ajax({
                                        type: 'POST',
                                        url: 'php/getNewestMeasurement.php',
                                        data: "",
                                        async: true,
                                        dataType: 'json',
                                        success: function (data) {
                                            //data is {}
                                            //console.log(data);
                                            var measurements = data;
                                            x = Date.parse(measurements['time_measured'] + ' UTC');
                                            //console.log(x);
                                            if (x > lastMeasurement) {
                                                lastMeasurement = x;
                                                y[0] = parseFloat(measurements['temperature_air']);
                                                y[1] = parseFloat(measurements['temperature_water']);
                                                y[2] = parseFloat(measurements['speed_wind']);
                                                y[3] = parseFloat(measurements['speed_boat']);

                                                for (var i = 0; i < series.length - 1; i++) {
                                                    series[i].addPoint([x, y[i]], (i + 2 === series.length), true);
                                                }

                                            } else {
                                                x = (new Date()).getTime();
                                            }
                                            //done: Dann in onload von Highcharts einfügen
                                            //done: Y[0] und so und checken ob der Punkt bei der zeit schon existiert
                                            //done: bei .csv dann auch die Ziet einfügen, dann muss aber die Zeit vom Raspi richtig sein
                                            //done: TODO: ei upload mal probieren random werte vom Raspi auf ftp server
                                            //Die lon und lat dann in die maps rein
                                            //
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
                    text: 'Live Temperatur'
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