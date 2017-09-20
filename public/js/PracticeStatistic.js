var MyDate = new Date();
var CurrentYear = MyDate.getFullYear();
var SearchValue=document.getElementById('name');
var GroupValue=document.getElementById('groupName');

var terminal = null;

var level = $('#getlevel').val();
var unit = $('#getunit').val();
var group = $('#getgroup').val();
var name = $('#getname').val();
$(document).ready(function(){
    Calendat_function();

    if (level != '校级' && level != '大组长')
    {
        if (level == '院级')
        {
            $.ajax({
                type: "get",
                async: false,
                url:"/PracticeStatisticsData",
                data: {
                    year1:$("#year1").val(),
                    year2:$("#year2").val(),
                    semester:$("#terminal").val().match(/\d/g),
                    unit:SearchValue.value,
                    value1:$('#weighted_value1').val(),
                    value2:$('#weighted_value2').val(),
                    value3:$('#weighted_value3').val(),
                    value4:$('#weighted_value4').val(),
                    value5:$('#weighted_value5').val()
                },
                success: function (result) {
                    //理论课授课总体评价
                    index_name_teaching = [];
                    index_value_teaching = [];

                    for (var j = 0;j<result.ChartTeaching.length;j++) {
                        for (var i = 0; i < result.ChartTeaching.length; i++) {
                            console.log(result.ChartTeaching[i]['level']);
                            if (result.ChartTeaching[i]['level'] == "明显不足" && index_name_teaching.length == 0) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "不足" && index_name_teaching.length == 1) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "正常" && index_name_teaching.length == 2) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "满意" && index_name_teaching.length == 3) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "非常满意" && index_name_teaching.length == 4) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                        }
                    }
                    //理论课听课总体评价
                    index_name_learning = [];
                    index_value_learning = [];

                    for (var j = 0;j<result.ChartLearning.length;j++) {
                        for (var i = 0;i<result.ChartLearning.length;i++) {
                            if (result.ChartLearning[i]['level'] == "明显不足" && index_name_learning.length == 0) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "不足" && index_name_learning.length == 1) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "正常" && index_name_learning.length == 2) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "满意" && index_name_learning.length == 3) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "非常满意" && index_name_learning.length == 4) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                        }
                    }

                    //评价项目（细项）得分情况---按评价数量排
                    index_name_MinorByCount = [];

                    for (var i = 0;i<result.ChartYAxis.length;i++)
                    {
                        index_name_MinorByCount.push(result.ChartYAxis[i]['LEVEL4'])

                    }

                    value_Satisfactory = result.ChartMinorByCount['满意'];
                    value_VerySatisfactory = result.ChartMinorByCount['非常满意'];
                    value_Deficient = result.ChartMinorByCount['不足'];
                    value_ObviouslyDeficient= result.ChartMinorByCount['明显不足'];
                    value_Normal= result.ChartMinorByCount['正常'];

                    //评价项目（细项）得分情况---按平均分排
                    index_name_MinorByAVG = [];

                    for(var i=0; i<result.ChartMinorByAVG.length; i++)
                    {
                        index_name_MinorByAVG.push(result.ChartMinorByAVG[i])
                    }

                    //评价项目（大项）得分情况
                    index_value_MajorTerm = [];
                    for(var i=0; i<result.ChartMajorTerm.length; i++)
                    {
                        index_value_MajorTerm.push(result.ChartMajorTerm[i])
                    }


                    BarChartTeachingOverallEvaluation ();
                    BarChartLearningOverallEvaluation ();
                    BarChartMajorTermEvaluation ();
                    BarChartMinorTermEvaluationByAVG ();
                    BarCharMinorTermEvaluationByCount ();

                }

            })
        }
        if (level == '小组长')
        {
            $.ajax({
                type: "get",
                async: false,
                url:"/PracticeStatisticsData",
                data: {
                    year1:$("#year1").val(),
                    year2:$("#year2").val(),
                    semester:$("#terminal").val().match(/\d/g),
                    group:group,
                    value1:$('#weighted_value1').val(),
                    value2:$('#weighted_value2').val(),
                    value3:$('#weighted_value3').val(),
                    value4:$('#weighted_value4').val(),
                    value5:$('#weighted_value5').val()
                },
                success: function (result) {
                    //理论课授课总体评价
                    index_name_teaching = [];
                    index_value_teaching = [];

                    for (var j = 0;j<result.ChartTeaching.length;j++) {
                        for (var i = 0; i < result.ChartTeaching.length; i++) {
                            console.log(result.ChartTeaching[i]['level']);
                            if (result.ChartTeaching[i]['level'] == "明显不足" && index_name_teaching.length == 0) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "不足" && index_name_teaching.length == 1) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "正常" && index_name_teaching.length == 2) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "满意" && index_name_teaching.length == 3) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "非常满意" && index_name_teaching.length == 4) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                        }
                    }
                    //理论课听课总体评价
                    index_name_learning = [];
                    index_value_learning = [];

                    for (var j = 0;j<result.ChartLearning.length;j++) {
                        for (var i = 0;i<result.ChartLearning.length;i++) {
                            if (result.ChartLearning[i]['level'] == "明显不足" && index_name_learning.length == 0) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "不足" && index_name_learning.length == 1) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "正常" && index_name_learning.length == 2) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "满意" && index_name_learning.length == 3) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "非常满意" && index_name_learning.length == 4) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                        }
                    }

                    //评价项目（细项）得分情况---按评价数量排
                    index_name_MinorByCount = [];

                    for (var i = 0;i<result.ChartYAxis.length;i++)
                    {
                        index_name_MinorByCount.push(result.ChartYAxis[i]['LEVEL4'])

                    }

                    value_Satisfactory = result.ChartMinorByCount['满意'];
                    value_VerySatisfactory = result.ChartMinorByCount['非常满意'];
                    value_Deficient = result.ChartMinorByCount['不足'];
                    value_ObviouslyDeficient= result.ChartMinorByCount['明显不足'];
                    value_Normal= result.ChartMinorByCount['正常'];

                    //评价项目（细项）得分情况---按平均分排
                    index_name_MinorByAVG = [];

                    for(var i=0; i<result.ChartMinorByAVG.length; i++)
                    {
                        index_name_MinorByAVG.push(result.ChartMinorByAVG[i])
                    }

                    //评价项目（大项）得分情况
                    index_value_MajorTerm = [];
                    for(var i=0; i<result.ChartMajorTerm.length; i++)
                    {
                        index_value_MajorTerm.push(result.ChartMajorTerm[i])
                    }


                    BarChartTeachingOverallEvaluation ();
                    BarChartLearningOverallEvaluation ();
                    BarChartMajorTermEvaluation ();
                    BarChartMinorTermEvaluationByAVG ();
                    BarCharMinorTermEvaluationByCount ();

                }

            })
        }
    }
    else {
        $.ajax({
            type: "get",
            async: false,
            url:"/PracticeStatisticsData",
            data: {
                year1:$("#year1").val(),
                year2:$("#year2").val(),
                semester:$("#terminal").val().match(/\d/g),
                value1:$('#weighted_value1').val(),
                value2:$('#weighted_value2').val(),
                value3:$('#weighted_value3').val(),
                value4:$('#weighted_value4').val(),
                value5:$('#weighted_value5').val()
            },
            success: function (result) {
                //理论课授课总体评价
                index_name_teaching = [];
                index_value_teaching = [];

                for (var j = 0;j<result.ChartTeaching.length;j++) {
                    for (var i = 0; i < result.ChartTeaching.length; i++) {
                        console.log(result.ChartTeaching[i]['level']);
                        if (result.ChartTeaching[i]['level'] == "明显不足" && index_name_teaching.length == 0) {
                            index_name_teaching.push(result.ChartTeaching[i]['level']);
                            index_value_teaching.push(result.ChartTeaching[i]['num'])
                        }
                        else if (result.ChartTeaching[i]['level'] == "不足" && index_name_teaching.length == 1) {
                            index_name_teaching.push(result.ChartTeaching[i]['level']);
                            index_value_teaching.push(result.ChartTeaching[i]['num'])
                        }
                        else if (result.ChartTeaching[i]['level'] == "正常" && index_name_teaching.length == 2) {
                            index_name_teaching.push(result.ChartTeaching[i]['level']);
                            index_value_teaching.push(result.ChartTeaching[i]['num'])
                        }
                        else if (result.ChartTeaching[i]['level'] == "满意" && index_name_teaching.length == 3) {
                            index_name_teaching.push(result.ChartTeaching[i]['level']);
                            index_value_teaching.push(result.ChartTeaching[i]['num'])
                        }
                        else if (result.ChartTeaching[i]['level'] == "非常满意" && index_name_teaching.length == 4) {
                            index_name_teaching.push(result.ChartTeaching[i]['level']);
                            index_value_teaching.push(result.ChartTeaching[i]['num'])
                        }
                    }
                }

                //理论课听课总体评价
                index_name_learning = [];
                index_value_learning = [];

                for (var j = 0;j<result.ChartLearning.length;j++) {
                    for (var i = 0;i<result.ChartLearning.length;i++) {
                        if (result.ChartLearning[i]['level'] == "明显不足" && index_name_learning.length == 0) {
                            index_name_learning.push(result.ChartLearning[i]['level']);
                            index_value_learning.push(result.ChartLearning[i]['num'])
                        }
                        else if (result.ChartLearning[i]['level'] == "不足" && index_name_learning.length == 1) {
                            index_name_learning.push(result.ChartLearning[i]['level']);
                            index_value_learning.push(result.ChartLearning[i]['num'])
                        }
                        else if (result.ChartLearning[i]['level'] == "正常" && index_name_learning.length == 2) {
                            index_name_learning.push(result.ChartLearning[i]['level']);
                            index_value_learning.push(result.ChartLearning[i]['num'])
                        }
                        else if (result.ChartLearning[i]['level'] == "满意" && index_name_learning.length == 3) {
                            index_name_learning.push(result.ChartLearning[i]['level']);
                            index_value_learning.push(result.ChartLearning[i]['num'])
                        }
                        else if (result.ChartLearning[i]['level'] == "非常满意" && index_name_learning.length == 4) {
                            index_name_learning.push(result.ChartLearning[i]['level']);
                            index_value_learning.push(result.ChartLearning[i]['num'])
                        }
                    }
                }

                //评价项目（细项）得分情况---按评价数量排
                index_name_MinorByCount = [];

                for (var i = 0;i<result.ChartYAxis.length;i++)
                {
                    index_name_MinorByCount.push(result.ChartYAxis[i]['LEVEL4'])

                }

                value_Satisfactory = result.ChartMinorByCount['满意'];
                value_VerySatisfactory = result.ChartMinorByCount['非常满意'];
                value_Deficient = result.ChartMinorByCount['不足'];
                value_ObviouslyDeficient= result.ChartMinorByCount['明显不足'];
                value_Normal= result.ChartMinorByCount['正常'];


                //评价项目（细项）得分情况---按平均分排
                index_name_MinorByAVG = [];

                for(var i=0; i<result.ChartMinorByAVG.length; i++)
                {
                    index_name_MinorByAVG.push(result.ChartMinorByAVG[i])
                }

                //评价项目（大项）得分情况
                index_value_MajorTerm = [];
                for(var i=0; i<result.ChartMajorTerm.length; i++)
                {
                    index_value_MajorTerm.push(result.ChartMajorTerm[i])
                }


                BarChartTeachingOverallEvaluation ();
                BarChartLearningOverallEvaluation ();
                BarChartMajorTermEvaluation ();
                BarChartMinorTermEvaluationByAVG ();
                BarCharMinorTermEvaluationByCount ();

            }

        })
    }


    $('#search').on('click',function(){
        checkNumber();

        if (level != '校级' && level != '大组长')
        {
            if (level == '院级')
            {
                $.ajax({
                    type: "get",
                    async: false,
                    url:"/PracticeStatisticsData",
                    data: {
                        year1:$("#year1").val(),
                        year2:$("#year2").val(),
                        semester:$("#terminal").val().match(/\d/g),
                        unit:SearchValue.value,
                        value1:$('#weighted_value1').val(),
                        value2:$('#weighted_value2').val(),
                        value3:$('#weighted_value3').val(),
                        value4:$('#weighted_value4').val(),
                        value5:$('#weighted_value5').val()
                    },
                    success: function (result) {
                        //理论课授课总体评价
                        index_name_teaching = [];
                        index_value_teaching = [];

                        for (var j = 0;j<result.ChartTeaching.length;j++) {
                            for (var i = 0; i < result.ChartTeaching.length; i++) {
                                console.log(result.ChartTeaching[i]['level']);
                                if (result.ChartTeaching[i]['level'] == "明显不足" && index_name_teaching.length == 4) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                                else if (result.ChartTeaching[i]['level'] == "不足" && index_name_teaching.length == 3) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                                else if (result.ChartTeaching[i]['level'] == "正常" && index_name_teaching.length == 2) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                                else if (result.ChartTeaching[i]['level'] == "满意" && index_name_teaching.length == 1) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                                else if (result.ChartTeaching[i]['level'] == "非常满意" && index_name_teaching.length == 0) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                            }
                        }

                        //理论课听课总体评价
                        index_name_learning = [];
                        index_value_learning = [];

                        for (var j = 0;j<result.ChartLearning.length;j++) {
                            for (var i = 0;i<result.ChartLearning.length;i++) {
                                if (result.ChartLearning[i]['level'] == "明显不足" && index_name_learning.length == 4) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                                else if (result.ChartLearning[i]['level'] == "不足" && index_name_learning.length == 3) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                                else if (result.ChartLearning[i]['level'] == "正常" && index_name_learning.length == 2) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                                else if (result.ChartLearning[i]['level'] == "满意" && index_name_learning.length == 1) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                                else if (result.ChartLearning[i]['level'] == "非常满意" && index_name_learning.length == 0) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                            }
                        }

                        //评价项目（细项）得分情况---按评价数量排
                        index_name_MinorByCount = [];
                        value = [];

                        for (var i = 0;i<result.ChartYAxis.length;i++)
                        {
                            index_name_MinorByCount.push(result.ChartYAxis[i]['LEVEL4'])

                        }

                        value_Satisfactory = result.ChartMinorByCount['满意'];
                        value_VerySatisfactory = result.ChartMinorByCount['非常满意'];
                        value_Deficient = result.ChartMinorByCount['不足'];
                        value_ObviouslyDeficient= result.ChartMinorByCount['明显不足'];
                        value_Normal= result.ChartMinorByCount['正常'];

                        //评价项目（细项）得分情况---按平均分排
                        index_name_MinorByAVG = [];

                        for(var i=0; i<result.ChartMinorByAVG.length; i++)
                        {
                            index_name_MinorByAVG.push(result.ChartMinorByAVG[i])
                        }

                        //评价项目（大项）得分情况
                        index_value_MajorTerm = [];
                        for(var i=0; i<result.ChartMajorTerm.length; i++)
                        {
                            index_value_MajorTerm.push(result.ChartMajorTerm[i])
                        }


                        BarChartTeachingOverallEvaluation ();
                        BarChartLearningOverallEvaluation ();
                        BarChartMajorTermEvaluation ();
                        BarChartMinorTermEvaluationByAVG ();
                        BarCharMinorTermEvaluationByCount ();

                    }

                })
            }
            if (level == '小组长')
            {
                $.ajax({
                    type: "get",
                    async: false,
                    url:"/PracticeStatisticsData",
                    data: {
                        year1:$("#year1").val(),
                        year2:$("#year2").val(),
                        semester:$("#terminal").val().match(/\d/g),
                        group:group,
                        value1:$('#weighted_value1').val(),
                        value2:$('#weighted_value2').val(),
                        value3:$('#weighted_value3').val(),
                        value4:$('#weighted_value4').val(),
                        value5:$('#weighted_value5').val()
                    },
                    success: function (result) {
                        //理论课授课总体评价
                        index_name_teaching = [];
                        index_value_teaching = [];

                        for (var j = 0;j<result.ChartTeaching.length;j++) {
                            for (var i = 0; i < result.ChartTeaching.length; i++) {
                                console.log(result.ChartTeaching[i]['level']);
                                if (result.ChartTeaching[i]['level'] == "明显不足" && index_name_teaching.length == 4) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                                else if (result.ChartTeaching[i]['level'] == "不足" && index_name_teaching.length == 3) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                                else if (result.ChartTeaching[i]['level'] == "正常" && index_name_teaching.length == 2) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                                else if (result.ChartTeaching[i]['level'] == "满意" && index_name_teaching.length == 1) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                                else if (result.ChartTeaching[i]['level'] == "非常满意" && index_name_teaching.length == 0) {
                                    index_name_teaching.push(result.ChartTeaching[i]['level']);
                                    index_value_teaching.push(result.ChartTeaching[i]['num'])
                                }
                            }
                        }

                        //理论课听课总体评价
                        index_name_learning = [];
                        index_value_learning = [];

                        for (var j = 0;j<result.ChartLearning.length;j++) {
                            for (var i = 0;i<result.ChartLearning.length;i++) {
                                if (result.ChartLearning[i]['level'] == "明显不足" && index_name_learning.length == 4) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                                else if (result.ChartLearning[i]['level'] == "不足" && index_name_learning.length == 3) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                                else if (result.ChartLearning[i]['level'] == "正常" && index_name_learning.length == 2) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                                else if (result.ChartLearning[i]['level'] == "满意" && index_name_learning.length == 1) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                                else if (result.ChartLearning[i]['level'] == "非常满意" && index_name_learning.length == 0) {
                                    index_name_learning.push(result.ChartLearning[i]['level']);
                                    index_value_learning.push(result.ChartLearning[i]['num'])
                                }
                            }
                        }

                        //评价项目（细项）得分情况---按评价数量排
                        index_name_MinorByCount = [];
                        value = [];

                        for (var i = 0;i<result.ChartYAxis.length;i++)
                        {
                            index_name_MinorByCount.push(result.ChartYAxis[i]['LEVEL4'])

                        }

                        value_Satisfactory = result.ChartMinorByCount['满意'];
                        value_VerySatisfactory = result.ChartMinorByCount['非常满意'];
                        value_Deficient = result.ChartMinorByCount['不足'];
                        value_ObviouslyDeficient= result.ChartMinorByCount['明显不足'];
                        value_Normal= result.ChartMinorByCount['正常'];

                        //评价项目（细项）得分情况---按平均分排
                        index_name_MinorByAVG = [];

                        for(var i=0; i<result.ChartMinorByAVG.length; i++)
                        {
                            index_name_MinorByAVG.push(result.ChartMinorByAVG[i])
                        }

                        //评价项目（大项）得分情况
                        index_value_MajorTerm = [];
                        for(var i=0; i<result.ChartMajorTerm.length; i++)
                        {
                            index_value_MajorTerm.push(result.ChartMajorTerm[i])
                        }


                        BarChartTeachingOverallEvaluation ();
                        BarChartLearningOverallEvaluation ();
                        BarChartMajorTermEvaluation ();
                        BarChartMinorTermEvaluationByAVG ();
                        BarCharMinorTermEvaluationByCount ();
                    }

                })
            }
        }
        else {
            $.ajax({
                type: "get",
                async: false,
                url:"/PracticeStatisticsData",
                data: {
                    year1:$("#year1").val(),
                    year2:$("#year2").val(),
                    semester:$("#terminal").val().match(/\d/g),
                    value1:$('#weighted_value1').val(),
                    value2:$('#weighted_value2').val(),
                    value3:$('#weighted_value3').val(),
                    value4:$('#weighted_value4').val(),
                    value5:$('#weighted_value5').val()
                },
                success: function (result) {
                    //理论课授课总体评价
                    index_name_teaching = [];
                    index_value_teaching = [];

                    for (var j = 0;j<result.ChartTeaching.length;j++) {
                        for (var i = 0; i < result.ChartTeaching.length; i++) {
                            console.log(result.ChartTeaching[i]['level']);
                            if (result.ChartTeaching[i]['level'] == "明显不足" && index_name_teaching.length == 4) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "不足" && index_name_teaching.length == 3) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "正常" && index_name_teaching.length == 2) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "满意" && index_name_teaching.length == 1) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                            else if (result.ChartTeaching[i]['level'] == "非常满意" && index_name_teaching.length == 0) {
                                index_name_teaching.push(result.ChartTeaching[i]['level']);
                                index_value_teaching.push(result.ChartTeaching[i]['num'])
                            }
                        }
                    }

                    //理论课听课总体评价
                    index_name_learning = [];
                    index_value_learning = [];

                    for (var j = 0;j<result.ChartLearning.length;j++) {
                        for (var i = 0;i<result.ChartLearning.length;i++) {
                            if (result.ChartLearning[i]['level'] == "明显不足" && index_name_learning.length == 4) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "不足" && index_name_learning.length == 3) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "正常" && index_name_learning.length == 2) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "满意" && index_name_learning.length == 1) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                            else if (result.ChartLearning[i]['level'] == "非常满意" && index_name_learning.length == 0) {
                                index_name_learning.push(result.ChartLearning[i]['level']);
                                index_value_learning.push(result.ChartLearning[i]['num'])
                            }
                        }
                    }

                    //评价项目（细项）得分情况---按评价数量排
                    index_name_MinorByCount = [];
                    value = [];

                    for (var i = 0;i<result.ChartYAxis.length;i++)
                    {
                        index_name_MinorByCount.push(result.ChartYAxis[i]['LEVEL4'])

                    }

                    value_Satisfactory = result.ChartMinorByCount['满意'];
                    value_VerySatisfactory = result.ChartMinorByCount['非常满意'];
                    value_Deficient = result.ChartMinorByCount['不足'];
                    value_ObviouslyDeficient= result.ChartMinorByCount['明显不足'];
                    value_Normal= result.ChartMinorByCount['正常'];

                    //评价项目（细项）得分情况---按平均分排
                    index_name_MinorByAVG = [];

                    for(var i=0; i<result.ChartMinorByAVG.length; i++)
                    {
                        index_name_MinorByAVG.push(result.ChartMinorByAVG[i])
                    }

                    //评价项目（大项）得分情况
                    index_value_MajorTerm = [];
                    for(var i=0; i<result.ChartMajorTerm.length; i++)
                    {
                        index_value_MajorTerm.push(result.ChartMajorTerm[i])
                    }


                    BarChartTeachingOverallEvaluation ();
                    BarChartLearningOverallEvaluation ();
                    BarChartMajorTermEvaluation ();
                    BarChartMinorTermEvaluationByAVG ();
                    BarCharMinorTermEvaluationByCount ();

                }

            })
        }


    })
})


//理论课授课总体评价
function BarChartTeachingOverallEvaluation (){
// 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('TChartOne'));
    // 指定图表的配置项和数据
    var option = {
        toolbox: {
            // y: 'bottom',
            feature: {
                saveAsImage: {
                    pixelRatio: 2
                }
            }
        },
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}:\n{c}%'

        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },

        xAxis: {
            data: index_name_teaching
        },

        yAxis: {
            type: 'value',
            axisLabel:{

                formatter:'{value}%'}

        },
        series: [{
            name: '得分情况',
            type: 'bar',
            barWidth: 60,
            label: {
                normal: {
                    show: true,
                    position:'top'
                }
            },
            data: index_value_teaching
        }]
    };

    myChart.setOption(option);

}

//理论课听课总体评价
function BarChartLearningOverallEvaluation (){
// 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('TChartTwo'));
    // 指定图表的配置项和数据
    var option = {
        toolbox: {
            // y: 'bottom',
            feature: {
                saveAsImage: {
                    pixelRatio: 2
                }
            }
        },
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b}:\n{c}%'

        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },

        xAxis: {
            data: index_name_learning,

        },

        yAxis: {
            type: 'value',
            axisLabel:{

                formatter:'{value}%'}

        },
        series: [{
            name: '得分情况',
            type: 'bar',
            barWidth: 60,
            label: {
                normal: {
                    show: true,
                    position:'top'
                }
            },
            data: index_value_learning
        }]
    };

    myChart.setOption(option);

}

//评价项目（大项）得分情况
function BarChartMajorTermEvaluation(){

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('TChartThree'));
    // 指定图表的配置项和数据
    var option = {
        toolbox: {
            // y: 'bottom',
            feature: {
                saveAsImage: {
                    pixelRatio: 2
                }
            }
        },
        tooltip: {},
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },

        xAxis: {
            data: ["授课态度","授课内容","授课方法","课件与板书","教学效果"]
        },
        yAxis: {
            type: 'value',
        },
        series: [{
            name: '得分情况',
            type: 'bar',
            barWidth: 60,
            label: {
                normal: {
                    show: true,
                    position:'top'
                }
            },
            data: index_value_MajorTerm
        }]
    };

    myChart.setOption(option);

}

//评价项目（细项）得分情况---按平均分排
function BarChartMinorTermEvaluationByAVG(){
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('TChartFive'));
    // 指定图表的配置项和数据
    var option = {
        toolbox: {
            // y: 'bottom',
            feature: {
                saveAsImage: {
                    pixelRatio: 2
                }
            }
        },
        tooltip: {},
        grid: {
            left: '3%',
            right: '4%',
            bottom: '30%' ,
            containLabel: true
        },

        xAxis : [
            {
                type : 'category',
                data : index_name_MinorByCount,
                axisLabel:{
                    interval: 0 ,
                    rotate:30
                }
            }
        ],
        yAxis: {
            type: 'value',
        },
        series: [{
            name: '得分情况',
            type: 'bar',
            barWidth: 60,
            label: {
                normal: {
                    show: true,
                    position:'top'
                }
            },
            data: index_name_MinorByAVG
        }]
    };

    myChart.setOption(option);
}

//评价项目（细项）得分情况---按评价数量排
function BarCharMinorTermEvaluationByCount(){

    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('TChartSix'));
    // 指定图表的配置项和数据
    var option = {
        toolbox: {
            // y: 'bottom',
            feature: {
                saveAsImage: {
                    pixelRatio: 2
                }
            }
        },
        tooltip : {},
        grid: {

            bottom: '35%'

        },
        legend: {
            data: ["非常满意", "满意", "正常", "存在不足", "存在明显不足"]
        },

        xAxis : [
            {
                type : 'category',
                data : index_name_MinorByCount,
                axisLabel:{
                    interval: 0 ,
                    rotate:30
                }
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'非常满意',
                type:'bar',
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                data:value_VerySatisfactory

            },
            {
                name:'满意',
                type:'bar',
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                data:value_Satisfactory

            },
            {
                name:'正常',
                type:'bar',
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                data:value_Normal

            },{
                name:'存在不足',
                type:'bar',
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                data:value_Deficient

            },{
                name:'存在明显不足',
                type:'bar',
                label: {
                    normal: {
                        show: true,
                        position: 'top'
                    }
                },
                data:value_ObviouslyDeficient

            },
        ]
    };
    myChart.setOption(option);
}

function checkNumber()
{
    var reg = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/;
    var str = document.getElementById('weighted_value1').value;
    if(!reg.test(str)){
        alert('权重值必须为数字！')
    }
}
function Calendat_function()
{
    Get_CurrentYear(CurrentYear);
    $("#calender").val( $("#year1").val()+"学年 ～ "+$("#year2").val()+"学年："+$("#terminal").val());
    $("#calender").focus(function (){
        $("#dtBox").show();
        Get_CurrentYear(CurrentYear);
    });
//        X 按钮
    $(".dtpicker-close").click(function(){
        $("#dtBox").hide();
    });
//        取消按钮
    $(".dtpicker-buttonClear").click(function(){
        $("#dtBox").hide();
    });
//        确定按钮
    $(".dtpicker-buttonSet").click(function(){
        $("#dtBox").hide();
        $("#calender").val( $("#year1").val()+"学年 ～ "+$("#year2").val()+"学年："+$("#terminal").val());
    });

    //year1 + 按钮
    $("#year1_class1").click(function(){
        CurrentYear++;
        $("#year1").val(CurrentYear);
        $("#year2").val(parseInt(CurrentYear)+1);
    });
    //year1 - 按钮
    $("#year1_class2").click(function(){
        CurrentYear--;
        $("#year1").val(CurrentYear);
        $("#year2").val(parseInt(CurrentYear)+1);
    });
    //学期选择的 +、- 按钮
    $(".dtpicker-compButtonEnable").click(function(){
        if ( $("#terminal").val() == "第1学期")
            $("#terminal").val("第2学期");
        else
            $("#terminal").val("第1学期");
    })
    $("#year1").bind('keyup',function(){
        CurrentYear=$("#year1").val();
        if( CurrentYear.length==4)
        {
            $("#year1").blur(function(){
                console.log(CurrentYear.match(/^\d{4}$/));
                if ( parseInt(CurrentYear)>2050 ||  parseInt(CurrentYear)<=1970 || CurrentYear.match(/^\d{4}$/) == null)
                {
                    alert("请输入正确的4位数字！有效范围为1970～2049");
                    CurrentYear = MyDate.getFullYear();
                }
                $("#year1").val(CurrentYear);
                $("#year2").val(parseInt(CurrentYear)+1);

            });
        }
    });
    function Get_CurrentYear(year)
    {

        if ( (MyDate.getMonth()+1) <8 && (MyDate.getMonth()+1) >= 3)
        {
            $("#terminal").val("第2学期");
            terminal = 2;
            $("#year1").val(year-1);
            $("#year2").val(year);
        }
        else
        {
            $("#terminal").val("第1学期");
            terminal=1;

            if (MyDate.getMonth()+1 < 3)
            {
                $("#year1").val(year-1);
                $("#year2").val(year);
            }
            if(MyDate.getMonth()+1 >= 8)
            {
                $("#year1").val(year);
                $("#year2").val(year+1);
            }
        }

    }
}
