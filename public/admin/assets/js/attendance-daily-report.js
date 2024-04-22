/**
 * Dashboard Analytics
 */

'use strict';

(function() {
    let cardColor, headingColor, labelColor, shadeColor, grayColor;
    if (isDarkStyle) {
        cardColor = config.colors_dark.cardColor;
        labelColor = config.colors_dark.textMuted;
        headingColor = config.colors_dark.headingColor;
        shadeColor = 'dark';
        grayColor = '#5E6692'; // gray color is for stacked bar chart
    } else {
        cardColor = config.colors.cardColor;
        labelColor = config.colors.textMuted;
        headingColor = config.colors.headingColor;
        shadeColor = '';
        grayColor = '#817D8D';
    }

    //Daily Attendance Report
    // --------------------------------------------------------------------
    //Regular Employees
    var regularReportData = $('#regular-employees').data('regular-employees');
    const regularEmployeeEl = document.querySelector('#regular-employees'),
        regularEmployeeElConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.success],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: shadeColor,
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                data: regularReportData
            }],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: false
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            }
        };
    if (typeof regularEmployeeEl !== undefined && regularEmployeeEl !== null) {
        const regularEmployeesGenerated = new ApexCharts(regularEmployeeEl, regularEmployeeElConfig);
        regularEmployeesGenerated.render();
    }

    //Late In Employees
    var lateInReportData = $('#late-in-employees').data('late-in-employees');
    const lateInEmployeeEl = document.querySelector('#late-in-employees'),
        lateInEmployeeElConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.warning],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: shadeColor,
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                data: lateInReportData
            }],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: false
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            }
        };
    if (typeof lateInEmployeeEl !== undefined && lateInEmployeeEl !== null) {
        const lateInEmployeesGenerated = new ApexCharts(lateInEmployeeEl, lateInEmployeeElConfig);
        lateInEmployeesGenerated.render();
    }

    //Half Day Employees
    var halfDayReportData = $('#half-day-employees').data('half-day-employees');
    const halfDayEmployeeEl = document.querySelector('#half-day-employees'),
        halfDayEmployeeElConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.primary],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: shadeColor,
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                data: halfDayReportData
            }],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: false
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            }
        };
    if (typeof halfDayEmployeeEl !== undefined && halfDayEmployeeEl !== null) {
        const halfDayEmployeeGenerated = new ApexCharts(halfDayEmployeeEl, halfDayEmployeeElConfig);
        halfDayEmployeeGenerated.render();
    }

    //Absent Employees
    var absentReportData = $('#absent-employees').data('absent-employees');
    const absentEmployeeEl = document.querySelector('#absent-employees'),
        absentEmployeeElConfig = {
            chart: {
                height: 130,
                type: 'area',
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                }
            },
            markers: {
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            grid: {
                show: false
            },
            colors: [config.colors.danger],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: shadeColor,
                    shadeIntensity: 0.8,
                    opacityFrom: 0.6,
                    opacityTo: 0.1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [{
                data: absentReportData
            }],
            xaxis: {
                show: true,
                lines: {
                    show: false
                },
                labels: {
                    show: false
                },
                stroke: {
                    width: 0
                },
                axisBorder: {
                    show: false
                }
            },
            yaxis: {
                stroke: {
                    width: 0
                },
                show: false
            },
            tooltip: {
                enabled: false
            }
        };
    if (typeof absentEmployeeEl !== undefined && absentEmployeeEl !== null) {
        const absentEmployeeGenerated = new ApexCharts(absentEmployeeEl, absentEmployeeElConfig);
        absentEmployeeGenerated.render();
    }
    //Daily Attendance Report

    // Filter form control to default size
    // setTimeout used for multilingual table initialization
    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
})();