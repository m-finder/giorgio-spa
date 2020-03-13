<template>
    <div>
        <b-container fluid>
            <b-row>
                <b-col md="4" sm="12">
                    <v-chart :options="option"/>
                </b-col>
                <b-col md="4" sm="12">
                    <v-chart :options="polar"/>
                </b-col>
                <b-col md="4" sm="12">
                    <v-chart :options="line"/>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>
<script>
    import ECharts from 'vue-echarts'
    // import "echarts/lib/chart/bar"
    // import 'echarts/lib/chart/line'
    // import 'echarts/lib/component/polar'
    // import 'echarts/lib/component/tooltip'
    // import 'echarts/lib/component/legend'
    import 'echarts'

    export default {
        name: 'Dashboard',
        components: {
            'v-chart': ECharts
        },
        data() {
            let data = []

            for (let i = 0; i <= 360; i++) {
                let t = i / 180 * Math.PI
                let r = Math.sin(2 * t) * Math.cos(2 * t)
                data.push([r, i])
            }
            return {
                option: {
                    title: {
                        text: "ECharts 入门示例"
                    },
                    tooltip: {},
                    legend: {
                        data: ["销量"]
                    },
                    xAxis: {
                        data: ["衬衫", "羊毛衫", "雪纺衫", "裤子", "高跟鞋", "袜子"]
                    },
                    yAxis: {},
                    series: [
                        {
                            name: "销量",
                            type: "bar",
                            data: [5, 20, 36, 10, 10, 20]
                        }
                    ]
                },
                polar: {
                    title: {
                        text: '极坐标双数值轴'
                    },
                    legend: {
                        data: ['line']
                    },
                    polar: {
                        center: ['50%', '54%']
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'cross'
                        }
                    },
                    angleAxis: {
                        type: 'value',
                        startAngle: 0
                    },
                    radiusAxis: {
                        min: 0
                    },
                    series: [
                        {
                            coordinateSystem: 'polar',
                            name: 'line',
                            type: 'line',
                            showSymbol: false,
                            data: data
                        }
                    ],
                    animationDuration: 2000
                },
                line: {
                    title: {
                        text: '折线图堆叠'
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        top: "8%",
                        data: ['邮件营销', '联盟广告', '视频广告', '直接访问', '搜索引擎']
                    },
                    toolbox: {
                        feature: {
                            saveAsImage: {}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [
                        {
                            name: '邮件营销',
                            type: 'line',
                            stack: '总量',
                            data: [120, 132, 101, 134, 90, 230, 210]
                        },
                        {
                            name: '联盟广告',
                            type: 'line',
                            stack: '总量',
                            data: [220, 182, 191, 234, 290, 330, 310]
                        },
                        {
                            name: '视频广告',
                            type: 'line',
                            stack: '总量',
                            data: [150, 232, 201, 154, 190, 330, 410]
                        },
                        {
                            name: '直接访问',
                            type: 'line',
                            stack: '总量',
                            data: [320, 332, 301, 334, 390, 330, 320]
                        },
                        {
                            name: '搜索引擎',
                            type: 'line',
                            stack: '总量',
                            data: [820, 932, 901, 934, 1290, 1330, 1320]
                        }
                    ]
                }
            }
        },
    }
</script>
<style lang="scss" scoped>
    .card-icon {
        width: 32px;
        height: 32px;
    }

    .row + .row {
        margin-top: 2rem;
    }

    .echarts {
        width: 100%;
        height: 350px;
    }
</style>
