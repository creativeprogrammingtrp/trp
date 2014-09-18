/*
 * MoonCake v1.3.1 - Dashboard Demo JS
 *
 * This file is part of MoonCake, an Admin template build for sale at ThemeForest.
 * For questions, suggestions or support request, please mail me at maimairel@yahoo.com
 *
 * Development Started:
 * July 28, 2012
 * Last Update:
 * December 07, 2012
 *
 */

;
(function($, window, document, undefined) {

    /* Use this object if you need to save Flot rendering callbacks to call it when needed */
    var FlotManager = function() {
    }

    FlotManager.prototype = {
        instances: {}, // id: callback, where id should be a selector string

        // Remove a function from the collection
        unregister: function(key) {
            if (typeof(this.instances[ key ]) !== 'undefined') {
                this.instances[ key ] = null;
            }
        },
        // Add a function to the collection
        register: function(key, selector, cb) {
            if (typeof(this.instances[ key ]) === 'undefined') {
                this.instances[ key ] = {
                    selector: selector,
                    callback: cb
                };
            }
        },
        // Call a function with id as the collection key
        updateByKey: function(key) {
            if (typeof(this.instances[ key ]) !== 'undefined')
                $(this.instances[ key ].selector).first().is(':visible') && this.instances[ key ].callback.call(this);
        },
        // Call a function by selector in the collection
        updateBySelector: function(selector) {
            $.each(this.instances, $.proxy(function(key, obj) {
                $(obj.selector)[0] === $(selector)[0] && this.updateByKey(key);
            }, this));
        },
        // Call all functions in the collection
        updateAll: function() {
            $.each(this.instances, $.proxy(function(key) {
                this.updateByKey(key);
            }, this));
        }
    };

    $.flotManager = new FlotManager;

    var demos = {
        trigometryCharts: function(key, selector) {
            if ($.fn.slider) {
                $('#math-x-range').slider({
                    min: -10, max: 10,
                    range: true,
                    step: 0.5,
                    values: [-5, 5],
                    ticks: [-10, '|', -8, '|', -6, '|', -4, '|', -2, '|', 0, '|', 2, '|', 4, '|', 6, '|', 8, '|', 10],
                    change: function(event, ui) {
                        plotNow();
                    }
                });
            }

            var target = $(selector),
                    options = {
                series: {
                    lines: {show: true},
                    points: {show: true}
                },
                tooltip: true,
                tooltipOptions: {},
                grid: {borderWidth: 0, hoverable: true}
            },
            plot = null;

            // define the plotting function to call each time the tab is shown
            function plotNow() {
                var data = [];
                var sin = [], cos = [];
                var colors = ['#115b74', '#e49600', '#cc2b36', '#e18876'];
                var sliderMin = $('#math-x-range').slider('values', 0);
                var sliderMax = $('#math-x-range').slider('values', 1);

                for (var j = 0; j < 2; j++) {
                    var dsin = [], dcos = [];
                    for (var i = sliderMin; i < sliderMax; i += 0.5) {
                        dsin.push([i, (j + 1) * (j + 3) * Math.sin(i)]);
                        dcos.push([i, (j + 1) * (j + 3) * Math.cos(i)]);
                    }
                    a = (j + 1) * (j + 3);
                    data.push({data: dsin, color: colors[j * 2], label: a + " sin(x)"});
                    data.push({data: dcos, color: colors[j * 2 + 1], label: a + " cos(x)"});
                }
                ;

                if (plot) {
                    plot.setData(data);
                    plot.setupGrid();
                    plot.draw();
                } else {
                    plot = $.plot(target, data, options);
                }
            }
            ;

            // Now register and render the chart
            $.flotManager.register(key, selector, plotNow);
        },
        fbInsights: function(key, selector) {
            if (!$.plot)
                return;

            var talkingAboutThis = [], d = [30, 29, 42, 29, 37, 37, 40, 31, 35, 17, 48, 41, 26, 25, 63, 55, 46, 33, 75, 54, 26, 21, 27, 59, 58, 50, 46, 116, 81, 61, 90],
                    newLikes = [], d1 = [12, 9, 10, 11, 16, 15, 11, 16, 15, 8, 15, 14, 11, 4, 27, 24, 20, 17, 28, 21, 13, 8, 6, 22, 24, 24, 13, 56, 33, 22, 36],
                    unlikes = [], d2 = [0, 1, 2, 0, 0, 1, 3, 0, 1, 1, 1, 2, 1, 1, 3, 0, 2, 0, 1, 2, 3, 0, 5, 3, 4, 0, 0, 1, 2, 1, 2],
                    target = $(selector),
                    plot = null;

            for (var i in d) {
                var dd = new Date(Date.UTC(2012, 6, parseInt(i, 10) + 1));
                talkingAboutThis.push([dd.getTime(), d[i]]);
            }
            for (var i in d1) {
                var dd = new Date(Date.UTC(2012, 6, parseInt(i, 10) + 1));
                newLikes.push([dd.getTime(), d1[i]]);
            }
            for (var i in d2) {
                var dd = new Date(Date.UTC(2012, 6, parseInt(i, 10) + 1));
                unlikes.push([dd.getTime(), d2[i]]);
            }

            var options = {
                series: {
                    lines: {
                        show: true,
                        fill: true,
                        lineWidth: 2,
                        fillColor: {colors: [{opacity: 0.1}, {opacity: 0.1}, {opacity: 0}]}
                    },
                    points: {show: true}
                },
                tooltip: true,
                tooltipOpts: {
                    content: '%y (%x)'
                },
                xaxis: {mode: 'time'},
                grid: {borderWidth: 0, hoverable: true}
            },
            data = [{
                    data: talkingAboutThis,
                    label: 'Daily People Talking About This',
                    color: '#26b'
                }, {
                    data: newLikes,
                    label: 'Daily New Likes',
                    color: '#cc2b36'
                }, {
                    data: unlikes,
                    label: 'Daily Unlikes',
                    color: '#f80',
                    points: {show: false}
                }];

            // Initialize Datepickers
            if ($.fn.datepicker) {
                $('.datepicker').datepicker({
                    dateFormat: 'yy-mm-dd',
                    autoClose: true,
                    hideIfNoPrevNext: true,
                    minDate: new Date(Date.UTC(2012, 6, 1)),
                    maxDate: new Date(Date.UTC(2012, 6, 31))
                });

                $('#dp1.datepicker').on('change', function(ev) {
                    var minDate = $(ev.target).datepicker('getDate');

                    $('#dp2.datepicker').datepicker('option', 'minDate', minDate);

                    options.xaxis.min = new Date(Date.UTC(
                            minDate.getFullYear(),
                            minDate.getMonth(),
                            minDate.getDate()
                            )).getTime();

                    plot = $.plot(target, data, options);
                }).datepicker('setDate', new Date(2012, 6, 1));

                $('#dp2.datepicker').on('change', function(ev) {
                    var maxDate = $(ev.target).datepicker('getDate');

                    $('#dp1.datepicker').datepicker('option', 'maxDate', maxDate);

                    options.xaxis.max = new Date(Date.UTC(
                            maxDate.getFullYear(),
                            maxDate.getMonth(),
                            maxDate.getDate()
                            )).getTime();

                    plot = $.plot(target, data, options);
                }).datepicker('setDate', new Date(2012, 6, 31));
            }

            // define the plotting function to call each time the tab is shown
            function plotNow() {
                plot || (plot = $.plot(target, data, options));
            }

            // Now register the function to the manager
            $.flotManager.register(key, selector, plotNow);
        },
        goalCharts: function(key, selector) {

            var goals = [],
                    actuals = [],
                    toggles = $('#demo-chart-03-toolbar'),
                    target = $(selector);

            for (var i = 0; i < 24; i++) {
                var goal = Math.floor(2400 + Math.random() * 600),
                        t = new Date(Date.UTC(2011, i, 1)).getTime() + (24 * 60 * 60 * 1000);

                goal = Math.ceil(goal / 10) * 10;
                goals.push([t, goal]);
                actuals.push([t, Math.floor(goal - (i * 15) + Math.random() * (i * 30))]);
            }

            var data = [
                {
                    data: goals,
                    label: "Target Revenue",
                    color: '#08c',
                    bars: {
                        show: true,
                        barWidth: 10 * 24 * 60 * 60 * 1000,
                        align: "center"
                    }
                }, {
                    data: actuals,
                    label: "Actual Revenue",
                    color: '#CC2B36',
                    lines: {
                        show: true
                    },
                    points: {
                        show: true,
                        radius: 4
                    }
                }
            ],
                    options = {
                xaxis: {
                    mode: 'time'
                },
                tooltip: true,
                tooltipOpts: {
                    content: '%x - %y',
                    dateFormat: '%b %y'
                },
                grid: {
                    borderWidth: 0,
                    hoverable: true
                }
            },
            plot = null;

            // define the plotting function to call each time the tab is shown
            function plotNow() {
                var d = [];
                toggles.find(':checkbox').each(function() {
                    if ($(this).is(':checked'))
                        d.push(data[$(this).attr("name").substr(4, 1)]);
                });
                if (d.length > 0) {
                    if (plot) {
                        plot.setData(d);
                        plot.draw();
                    } else {
                        plot = $.plot(target, d, options);
                    }
                }
            }
            ;

            toggles.find(':checkbox').on('change', function() {
                plotNow();
            });

            // Now register the function to the manager
            $.flotManager.register(key, selector, plotNow);
        },
        updatingChart: function(key, selector) {
            // we use an inline data source in the example, usually data would
            // be fetched from a server
            var data = [],
                    totalPoints = 200;

            function getRandomData() {
                if (data.length > 0)
                    data = data.slice(1);

                // do a random walk
                while (data.length < totalPoints) {
                    var prev = data.length > 0 ? data[data.length - 1] : 50;
                    var y = prev + Math.random() * 10 - 5;
                    if (y < 15)
                        y = 15;
                    if (y > 80)
                        y = 80;
                    data.push(y);
                }

                // zip the generated y values with the x values
                var res = [];
                for (var i = 0; i < data.length; ++i)
                    res.push([i, data[i]])
                return res;
            }

            var stockValue = [],
                    options = {
                yaxis: {min: 0, max: 100},
                xaxis: {min: 0, max: 100},
                series: {
                    lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: {colors: [{opacity: 0.4}, {opacity: 0}]},
                        steps: false
                    }
                },
                points: {
                    show: true
                },
                grid: {
                    borderWidth: 0
                }
            },
            target = $(selector),
                    plot = null,
                    _d = [
                {data: getRandomData(), label: 'Physical Memory', color: 'red', points: {show: false}},
                {data: stockValue, label: 'Avg. CPU Usage', color: 'green', lines: {fill: false}}
            ],
                    liveUpdate = true,
                    timeout = null;

            for (var x = 0; x < totalPoints; x += 5) {
                var y = Math.floor(50 - 15 + Math.random() * 30);
                stockValue.push([x, y]);
            }

            // define the plotting function to call each time the tab is shown
            function plotNow() {
                if (liveUpdate) {
                    _d[0].data = getRandomData();

                    if (plot) {
                        plot.setData(_d);
                        plot.draw();

                        for (var i = 1; i <= 3; i++) {
                            var value = $('#cs-' + i).circularStat('option', 'value');
                            value = value + (Math.random() * (value * 0.1)) - (value * 0.05);

                            $('#cs-' + i).circularStat('option', 'value', value);
                        }
                    } else {
                        plot = $.plot(target, _d, options);
                        liveUpdate = false;
                    }

                    timeout = setTimeout(plotNow, 1000);
                } else {
                    timeout && clearTimeout(timeout);
                }

                $.fn.iButton && $('#live-switch').iButton('repaint');
            }

            // Bind switch button to toggle on/off the live update
            if ($.fn.iButton) {
                $('#live-switch').iButton({
                    change: function(input) {
                        liveUpdate = $(input).is(':checked');
                        plotNow();
                    }
                });
            }

            // Now register the function to the manager
            $.flotManager.register(key, selector, plotNow);
        },
        fullCalendar: function(target) {
            var url = window.location + "/getAllEvents";
            if ($.fn.fullCalendar) {
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                // console.log("Date: " + y + " - " + m + " - " + d)


                target.fullCalendar({
                    header: {
                        left: 'prev next today',
                        center: 'title',
                        right: 'month agendaWeek agendaDay'
                    },
                    editable: true,
                    eventSources: [
                        {
                            url: url,
                            //color: 'red',
                            //textColor: 'yellow'
                        }
                    ],
//                    events: url,
//                    events: [
//                        {
//                            title: 'Test Event',
//                            start: new Date(Date.UTC(2013, 7, 19))
//                        },
//                        {
//                            title: 'Long Event',
//                            start: new Date(Date.UTC(y, m, d - 5)),
//                            end: new Date(Date.UTC(y, m, d - 2))
//                        },
//                        {
//                            id: 999,
//                            title: 'Repeating Event',
//                            start: new Date(Date.UTC(y, m, d - 3, 16, 0)),
//                            allDay: false
//                        },
//                        {
//                            id: 999,
//                            title: 'Repeating Event',
//                            start: new Date(Date.UTC(y, m, d + 4, 16, 0)),
//                            allDay: false
//                        },
//                        {
//                            title: 'Meeting',
//                            start: new Date(Date.UTC(y, m, d, 10, 30)),
//                            allDay: false
//                        },
//                        {
//                            title: 'Lunch',
//                            start: new Date(Date.UTC(y, m, d, 12, 0)),
//                            end: new Date(Date.UTC(y, m, d, 14, 0)),
//                            allDay: false
//                        },
//                        {
//                            title: 'Birthday Party',
//                            start: new Date(Date.UTC(y, m, d + 1, 19, 0)),
//                            end: new Date(Date.UTC(y, m, d + 1, 22, 30)),
//                            allDay: false
//                        },
//                        {
//                            title: 'Click for Google',
//                            start: new Date(Date.UTC(y, m, 28)),
//                            end: new Date(Date.UTC(y, m, 29)),
//                            url: 'http://google.com/'
//                        }
//                    ],
                    buttonText: {
                        prev: '<i class="icon-caret-left"></i>',
                        next: '<i class="icon-caret-right"></i>',
                        prevYear: '<i class="icon-caret-left"></i><i class="icon-caret-left"></i>',
                        nextYear: '<i class="icon-caret-right"></i><i class="icon-caret-right"></i>'
                    }
                });

            }

        }
    };

    $(document).ready(function() {

        // If Flot and $.flotManager is defined

        if ($.plot && $.flotManager) {
            //getDataUserChart();

            // Trigonometry Chart
            //demos.trigometryCharts('#math', '#demo-chart-01');

            // Fb Insights
            //demos.fbInsights('#fb', '#demo-chart-02');

            // Toys Distribution
            //demos.goalCharts('#revenue', '#demo-chart-03');
        }

        if ($.fn.sparkline) {
            $('.sparkline').sparkline('html', {enableTagOptions: true});
        }

        demos.fullCalendar($('#demo-calendar-01'));
    });

    $(window).load(function() {
        // When all page resources has finished loading
        if ($.plot && $.flotManager) {
        var today = new Date();
        var year = today.getFullYear();
        getDataUserChart(year);

        $("#btnSubmitChart").click(function() {
            var val = $("#txtYearOfChart").val();
            if (val != '') {
                var reg = new RegExp('^\\d+$');
                if(reg.test(val) == false) {                    
                    $('#errtxtYearOfChart').html('Please, enter correct year!');
                    $('#txtYearOfChart').css("background-color","#ffc6c6");
                    $('#txtYearOfChart').focus();
                } else {
                    getDataUserChart($("#txtYearOfChart").val());                      
                    $('#errtxtYearOfChart').html('');
                    $('#txtYearOfChart').css("background-color","transparent");
                }
            }
            else
                getDataUserChart(year);
        });

            $.flotManager.updateBySelector('#demo-chart-00');

            $('#dashboard-demo a[data-toggle="tab"]').on('shown', function(e) {
                var id = $(e.target).data('target');
                $.flotManager.updateByKey(id);
            });
        }
    });

})(jQuery, window, document);

function getDataUserChart(year) {
    var url = window.location + "/getDataUserChart";
    $("#loding_screen").css("display", "block");

    $.ajax({
        type: "POST",
        url: url,
        data: {year: year},
        dataType: "json",
        withCredentials: true,
        useDefaultXhrHeader: false,
        success: function(data) {
            var getData = $.parseJSON(data);

            //var str_month_year = getData.data_month_year_chart;
            // Users
            var str_user = getData.data_user_chart;
            // Orders
            var str_order = getData.data_order_chart;
            // Money
            var str_money = getData.data_money_chart;
            // Voucher
            var str_voucher = getData.data_voucher_chart;

            // ::::::::::::: //

            //var data_month_year_chart = str_month_year.split(',');
            var data_month_year_chart = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            // User
            var data_user_chart = str_user.split(',');
            // Order
            var data_order_chart = str_order.split(',');
            // Money
            var data_money_chart = str_money.split(',');
            // Voucher
            var data_voucher_chart = str_voucher.split(',');

//            console.log("Month_Year: " + data_month_year_chart);
//            console.log("Users: " + data_user_chart);
//            console.log("Orders: " + data_order_chart);
//            console.log("Money: " + data_money_chart);
//            console.log("Voucher: " + data_voucher_chart);

            // Start User Chart //
            var data_users = [
                [
                    [0, parseInt(data_user_chart[0])],
                    [1, parseInt(data_user_chart[1])],
                    [2, parseInt(data_user_chart[2])],
                    [3, parseInt(data_user_chart[3])],
                    [4, parseInt(data_user_chart[4])],
                    [5, parseInt(data_user_chart[5])],
                    [6, parseInt(data_user_chart[6])],
                    [7, parseInt(data_user_chart[7])],
                    [8, parseInt(data_user_chart[8])],
                    [9, parseInt(data_user_chart[9])],
                    [10, parseInt(data_user_chart[10])],
                    [11, parseInt(data_user_chart[11])]
                ]
            ];
            var option_users = {
                yaxis: {
                    position: "left", labelWidth: 24
                            //min: 1, max: 12
                },
                xaxis: {
                    position: "bottom",
                    //min: 0, max: 12,
                    ticks: [
                        [0, data_month_year_chart[0]],
                        [1, data_month_year_chart[1]],
                        [2, data_month_year_chart[2]],
                        [3, data_month_year_chart[3]],
                        [4, data_month_year_chart[4]],
                        [5, data_month_year_chart[5]],
                        [6, data_month_year_chart[6]],
                        [7, data_month_year_chart[7]],
                        [8, data_month_year_chart[8]],
                        [9, data_month_year_chart[9]],
                        [10, data_month_year_chart[10]],
                        [11, data_month_year_chart[11]]]
                            //mode: 'time'
                },
                series: {
                    lines: {show: true,
                        fill: true,
                        fillColor: {colors: [{opacity: 0.26}, {opacity: 0}]}
                    },
                    points: {show: true},
                    color: 'red',
                    label: 'Users'
                },
                grid: {
                    hoverable: true,
                    autoHighlight: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%y"
                            //dateFormat: '%m %Y'
                }
            };
            // End User Chart //

            // Start Order Chart //
            var data_orders = [
                [
                    [0, parseInt(data_order_chart[0])],
                    [1, parseInt(data_order_chart[1])],
                    [2, parseInt(data_order_chart[2])],
                    [3, parseInt(data_order_chart[3])],
                    [4, parseInt(data_order_chart[4])],
                    [5, parseInt(data_order_chart[5])],
                    [6, parseInt(data_order_chart[6])],
                    [7, parseInt(data_order_chart[7])],
                    [8, parseInt(data_order_chart[8])],
                    [9, parseInt(data_order_chart[9])],
                    [10, parseInt(data_order_chart[10])],
                    [11, parseInt(data_order_chart[11])]
                ]
            ];
            var option_orders = {
                yaxis: {
                    position: "left", labelWidth: 24
                            //min: 1, max: 12
                },
                xaxis: {
                    position: "bottom",
                    //min: 0, max: 12,
                    ticks: [
                        [0, data_month_year_chart[0]],
                        [1, data_month_year_chart[1]],
                        [2, data_month_year_chart[2]],
                        [3, data_month_year_chart[3]],
                        [4, data_month_year_chart[4]],
                        [5, data_month_year_chart[5]],
                        [6, data_month_year_chart[6]],
                        [7, data_month_year_chart[7]],
                        [8, data_month_year_chart[8]],
                        [9, data_month_year_chart[9]],
                        [10, data_month_year_chart[10]],
                        [11, data_month_year_chart[11]]]
                            //mode: 'time'
                },
                series: {
                    lines: {show: true,
                        fill: true,
                        fillColor: {colors: [{opacity: 0.26}, {opacity: 0}]}
                    },
                    points: {show: true},
                    color: 'green',
                    label: 'Order'
                },
                grid: {
                    hoverable: true,
                    autoHighlight: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%y"
                            //dateFormat: '%m %Y'
                }
            };
            // End Order Chart //

            // Start Money Chart //
            var data_money = [
                [
                    [0, parseFloat(data_money_chart[0])],
                    [1, parseFloat(data_money_chart[1])],
                    [2, parseFloat(data_money_chart[2])],
                    [3, parseFloat(data_money_chart[3])],
                    [4, parseFloat(data_money_chart[4])],
                    [5, parseFloat(data_money_chart[5])],
                    [6, parseFloat(data_money_chart[6])],
                    [7, parseFloat(data_money_chart[7])],
                    [8, parseFloat(data_money_chart[8])],
                    [9, parseFloat(data_money_chart[9])],
                    [10, parseFloat(data_money_chart[10])],
                    [11, parseFloat(data_money_chart[11])]
                ]
            ];
            var option_money = {
                yaxis: {
                    position: "left", labelWidth: 24
                            //min: 1, max: 12
                },
                xaxis: {
                    position: "bottom",
                    //min: 0, max: 12,
                    ticks: [
                        [0, data_month_year_chart[0]],
                        [1, data_month_year_chart[1]],
                        [2, data_month_year_chart[2]],
                        [3, data_month_year_chart[3]],
                        [4, data_month_year_chart[4]],
                        [5, data_month_year_chart[5]],
                        [6, data_month_year_chart[6]],
                        [7, data_month_year_chart[7]],
                        [8, data_month_year_chart[8]],
                        [9, data_month_year_chart[9]],
                        [10, data_month_year_chart[10]],
                        [11, data_month_year_chart[11]]]
                            //mode: 'time'
                },
                series: {
                    lines: {show: true,
                        fill: true,
                        fillColor: {colors: [{opacity: 0.26}, {opacity: 0}]}
                    },
                    points: {show: true},
                    color: 'blue',
                    label: 'Sell'
                },
                grid: {
                    hoverable: true,
                    autoHighlight: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%y"
                            //dateFormat: '%m %Y'
                }
            };
            // End Money Chart //

            // Start Voucher Chart //
            var data_vouchers = [
                [
                    [0, parseInt(data_voucher_chart[0])],
                    [1, parseInt(data_voucher_chart[1])],
                    [2, parseInt(data_voucher_chart[2])],
                    [3, parseInt(data_voucher_chart[3])],
                    [4, parseInt(data_voucher_chart[4])],
                    [5, parseInt(data_voucher_chart[5])],
                    [6, parseInt(data_voucher_chart[6])],
                    [7, parseInt(data_voucher_chart[7])],
                    [8, parseInt(data_voucher_chart[8])],
                    [9, parseInt(data_voucher_chart[9])],
                    [10, parseInt(data_voucher_chart[10])],
                    [11, parseInt(data_voucher_chart[11])]
                ]
            ];
            var option_vouchers = {
                yaxis: {
                    position: "left", labelWidth: 24
                            //min: 1, max: 12
                },
                xaxis: {
                    position: "bottom",
                    //min: 0, max: 12,
                    ticks: [
                        [0, data_month_year_chart[0]],
                        [1, data_month_year_chart[1]],
                        [2, data_month_year_chart[2]],
                        [3, data_month_year_chart[3]],
                        [4, data_month_year_chart[4]],
                        [5, data_month_year_chart[5]],
                        [6, data_month_year_chart[6]],
                        [7, data_month_year_chart[7]],
                        [8, data_month_year_chart[8]],
                        [9, data_month_year_chart[9]],
                        [10, data_month_year_chart[10]],
                        [11, data_month_year_chart[11]]]
                            //mode: 'time'
                },
                series: {
                    lines: {show: true,
                        fill: true,
                        fillColor: {colors: [{opacity: 0.26}, {opacity: 0}]}
                    },
                    points: {show: true},
                    color: 'orange',
                    label: 'Voucher'
                },
                grid: {
                    hoverable: true,
                    autoHighlight: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%y"
                            //dateFormat: '%m %Y'
                }
            };
            // End Voucher Chart //
            $("#title_chart").html("Year: " + year);
                        
            $.plot($("#userChart"), data_users, option_users);
            $.plot($("#orderChart"), data_orders, option_orders);
            $.plot($("#moneyChart"), data_money, option_money);
            $.plot($("#voucherChart"), data_vouchers, option_vouchers);
            
            $('canvas.base div', $("#userChart")).css('position', 'static');
            $('canvas.base div', $("#orderChart")).css('position', 'static');
            $('canvas.base div', $("#moneyChart")).css('position', 'static');
            $('canvas.base div', $("#voucherChart")).css('position', 'static');
            
            $("#userChart").css("width","100%");
            $("#orderChart").css("width","100%");
            $("#moneyChart").css("width","100%");
            $("#voucherChart").css("width","100%");
            
            $("#loding_screen").css("display", "none");
        },
        error: function() {
            alert("Loi getDataUserChart");
        }
    });
}