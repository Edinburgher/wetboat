$(document).ready(function () {

    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    const points = {};
    //luft temp
    //wasser temp
    //luftdruck
    //luftfeuchte
    points["temperature_air"] = [];
    points["temperature_water"] = [];
    points["speed_wind"] = [];
    points["speed_boat"] = [];
    points["pressure_air"] = [];
    points["humidity_air"] = [];
    points["lat_boat"] = [];
    points["lon_boat"] = [];

    userAction({
        data: "action=getMeasurements",
        success: function (rows) {
            //rows is [{}]
            rows = JSON.parse(rows);
            rows.forEach(function (elem) {
                const t = elem["time_measured"].split(/[- :]/);
                const time_measured = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5])).getTime();
                points["temperature_air"].push([
                    time_measured, parseFloat(elem["temperature_air"])
                ]);
                points["temperature_water"].push([
                    time_measured, parseFloat(elem["temperature_water"])
                ]);
                points["speed_wind"].push([
                    time_measured, parseFloat(elem["speed_wind"])
                ]);
                points["speed_boat"].push([
                    time_measured, parseFloat(elem["speed_boat"])
                ]);
                points["pressure_air"].push([
                    time_measured, parseFloat(elem["pressure_air"])
                ]);
                points["humidity_air"].push([
                    time_measured, parseFloat(elem["humidity_air"])
                ]);
                points["lat_boat"].push([
                    time_measured, parseFloat(elem["lat_boat"])
                ]);
                points["lon_boat"].push([
                    time_measured, parseFloat(elem["lon_boat"])
                ]);

            });

            // Create the chart
            $("#container").highcharts("StockChart", {
                chart: {
                    events: {
                        load: function () {
                            // set up the updating of the chart each second
                            const series = this.series;
                            let lastMeasurement = new Date(0);

                            getDelay(function (delayMS) {
                                function getNewestMeasurements() {
                                    //Measurement data
                                    userAction({
                                        data: "action=getNewestMeasurement",
                                        success: function (measurement) {
                                            //data is {}
                                            measurement = JSON.parse(measurement);
                                            const t = measurement["time_measured"].split(/[- :]/);
                                            const x = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
                                            const y = [];
                                            if (x > lastMeasurement) {
                                                lastMeasurement = x;
                                                const datestring =
                                                    (x.getDate() < 10 ? "0" : "") + x.getDate() + "." +
                                                    (x.getMonth() + 1 < 10 ? "0" : "") + (x.getMonth() + 1) + "." +
                                                    (x.getFullYear() + " ") +
                                                    (x.getHours() < 10 ? "0" : "") + x.getHours() + ":" +
                                                    (x.getMinutes() < 10 ? "0" : "") + x.getMinutes() + ":" +
                                                    (x.getSeconds() < 10 ? "0" : "") + x.getSeconds();
                                                $("#time").html(datestring);
                                                $("#airtemp").html(y[0] = parseFloat(measurement["temperature_air"]));
                                                $("#watertemp").html(y[1] = parseFloat(measurement["temperature_water"]));
                                                $("#windspeed").html(y[2] = parseFloat(measurement["speed_wind"]));
                                                $("#airpressure").html(y[3] = parseFloat(measurement["pressure_air"]));
                                                $("#airhumidity").html(y[4] = parseFloat(measurement["humidity_air"]));

                                                // for loop only until length-1 because last one is reerved for mini view at the bottom of the chart for navigation
                                                // i + 2 === series.length so it redraws only on the last one
                                                for (let i = 0; i < series.length - 1; i++) {
                                                    series[i].addPoint([x.getTime(), y[i]], (i + 2 === series.length), true);
                                                }

                                                setNewestBoatMarker(datestring, measurement["lat_boat"], measurement["lon_boat"]);
                                            }
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            console.log(errorThrown);
                                        }
                                    });
                                }

                                getNewestMeasurements();
                                setInterval(getNewestMeasurements, delayMS);
                            });
                        }
                    }
                },
                yAxis: [{ // Primary yAxis
                    id: "axisTemp",
                    gridLineWidth: 1,
                    showEmpty: false,
                    labels: {
                        enabled: ($(window).width() > 1000),
                        format: "{value}°C",
                        style: {
                            color: Highcharts.getOptions().colors[5]
                        }
                    },
                    title: {
                        text: "Temperature",
                        style: {
                            color: Highcharts.getOptions().colors[5]
                        }
                    },
                    opposite: false

                }, { // Secondary yAxis
                    id: "axisSpeed",
                    gridLineWidth: 1,
                    showEmpty: false,
                    title: {
                        text: "Geschwindigkeit",
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        enabled: ($(window).width() > 1000),
                        format: "{value}km/h",
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }

                }, { // Tertiary yAxis
                    id: "axisPercent",
                    gridLineWidth: 0,
                    showEmpty: false,
                    title: {
                        text: "Luftfeuchte",
                        style: {
                            "color": Highcharts.getOptions().colors[6]
                        }
                    },
                    labels: {
                        enabled: ($(window).width() > 1000),
                        format: "{value}%",
                        style: {
                            color: Highcharts.getOptions().colors[6]
                        }
                    },
                    opposite: true
                }, { // 4th yAxis
                    id: "axisPressure",
                    gridLineWidth: 0,
                    showEmpty: false,
                    title: {
                        text: "Luftdruck",
                        style: {
                            "color": Highcharts.getOptions().colors[4]
                        }
                    },
                    labels: {
                        enabled: ($(window).width() > 1000),
                        format: "{value}hPa",
                        style: {
                            color: Highcharts.getOptions().colors[4]
                        }
                    },
                    opposite: true
                }],
                tooltip: {
                    shared: true,
                    valueDecimals: 1
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
                        type: "all",
                        text: "All"
                    }],
                    inputEnabled: false,
                    selected: 0
                },
                title: {
                    text: "Live Messdaten"
                },
                legend: {
                    enabled: true,
                    maxHeight: 100
                },
                exporting: {
                    enabled: true
                },
                xAxis: {
                    type: "datetime"
                },

                series: [
                    {
                        name: "Lufttemperatur",
                        data: points["temperature_air"],
                        yAxis: "axisTemp",
                        color: Highcharts.getOptions().colors[5],
                        tooltip: {
                            valueSuffix: "°C"
                        }
                    }, {
                        name: "Wassertemperatur",
                        data: points["temperature_water"],
                        yAxis: "axisTemp",
                        color: Highcharts.getOptions().colors[5],
                        tooltip: {
                            valueSuffix: "°C"
                        }
                    }, {
                        name: "Windgeschwindigkeit",
                        data: points["speed_wind"],
                        yAxis: "axisSpeed",
                        color: Highcharts.getOptions().colors[0],
                        tooltip: {
                            valueSuffix: "km/h"
                        }
                    }, {
                        name: "Luftdruck",
                        data: points["pressure_air"],
                        yAxis: "axisPressure",
                        color: Highcharts.getOptions().colors[4],
                        tooltip: {
                            valueSuffix: "hPa"
                        }
                    }, {
                        name: "Luftfeuchtigkeit",
                        data: points["humidity_air"],
                        yAxis: "axisPercent",
                        color: Highcharts.getOptions().colors[6],
                        tooltip: {
                            valueSuffix: "%"
                        }
                    }]
            });

            function enableLabels(enable) {
                const chart = $("#container").highcharts();
                const yAxis = chart.yAxis;
                for (let i = 0; i < yAxis.length - 1; i++) {
                    yAxis[i].userOptions.labels.enabled = enable;
                    yAxis[i].update();
                }
            }

            $(window).resize(function () {
                if ($(window).width() < 960) {
                    enableLabels(false);
                }
                else {
                    enableLabels(true);
                }
            });
        }
    });
});