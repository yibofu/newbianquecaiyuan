<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>扁鹊财院-领先的财务解决方案供应商；防控财务亚健康，安全创造新财富</title>
    <meta name="title" content="扁鹊财院-领先的财务解决方案供应商。财务咨询，财税咨询，企业上市服务，企业并购服务。">
    <meta name="Keywords" content="财务咨询、股权设计、税务筹划"/>
    <meta name="description"
          content="扁鹊财院以分享财务管理智慧为使命，致力于让企业财务更加安全、利用财务技术创造更多利润、让财务管理更规范。平台隶属于北京大财有道科技有限公司，为企业提供专业的财务培训、财税筹划咨询、高端财务人员猎头、企业资产管理等一站式服务，并为创业型资本运作企业提供财务整体解决方案。"/>

    <link rel="stylesheet" href="/Public/APP/css/new_file.css"/>
    <script>
        (function (designWidth, maxWidth) {
            var doc = document,
                win = window;
            var docEl = doc.documentElement;
            var tid;
            var rootItem, rootStyle;

            function refreshRem() {
                var width = docEl.getBoundingClientRect().width;
                if (!maxWidth) {
                    maxWidth = 540;
                }
                ;
                if (width > maxWidth) {
                    width = maxWidth;
                }
                //与淘宝做法不同，直接采用简单的rem换算方法1rem=100px
                var rem = width * 100 / designWidth;
                //兼容UC开始
                rootStyle = "html{font-size:" + rem + 'px !important}';
                rootItem = document.getElementById('rootsize') || document.createElement("style");
                if (!document.getElementById('rootsize')) {
                    document.getElementsByTagName("head")[0].appendChild(rootItem);
                    rootItem.id = 'rootsize';
                }
                if (rootItem.styleSheet) {
                    rootItem.styleSheet.disabled || (rootItem.styleSheet.cssText = rootStyle)
                } else {
                    try {
                        rootItem.innerHTML = rootStyle
                    } catch (f) {
                        rootItem.innerText = rootStyle
                    }
                }
                //兼容UC结束
                docEl.style.fontSize = rem + "px";
            };
            refreshRem();

            win.addEventListener("resize", function () {
                clearTimeout(tid); //防止执行两次
                tid = setTimeout(refreshRem, 300);
            }, false);

            win.addEventListener("pageshow", function (e) {
                if (e.persisted) { // 浏览器后退的时候重新计算
                    clearTimeout(tid);
                    tid = setTimeout(refreshRem, 300);
                }
            }, false);

            if (doc.readyState === "complete") {
                doc.body.style.fontSize = "16px";
            } else {
                doc.addEventListener("DOMContentLoaded", function (e) {
                    doc.body.style.fontSize = "16px";
                }, false);
            }
        })(640, 640);


    </script>
    <style>
        .tdP {
            display: -webkit-box;
        }
    </style>
    <script>

    </script>
</head>
<body>
<header>
    <div class="contentCenter">
        <div class="imgLogo">
            <img src="/Public/APP/img/logo.png" width="55%" style="margin-top: 0.05rem;"/>
        </div>
        <ul class="headerTil">
            <li><a href="">首页</a></li>
            <li><a href="">陪伴计划</a></li>
            <li><a href="">财务课堂</a></li>
            <li><a href="">扁鹊课堂</a></li>
            <li><a href="">专家团队</a></li>
            <li><a href="">财税咨询</a></li>
        </ul>
    </div>
</header>
<div class="adFade" style="width: 0.86rem;height: 2.41rem;position: fixed;right: 0.05rem;top: 2rem;z-index: 11;">
    <a href="https://pct.zoosnet.net/LR/Chatpre.aspx?id=PCT10814050&cid=1505378341792725941906&lng=cn&sid=1523928999128495010952&p=http%3A//127.0.0.1%3A8020/NewWeb/index.html&rf1=&rf2=&msg=&d=1523929065003"><img
            src="/Public/APP/img/Adfade.png"/></a>
</div>
<comment>
    <div class="banner">
        <img src="/Public/APP/img/banne.jpg"/>
    </div>
    <div class="partOne">
        <div class="partTil">
            <h2>热点方案</h2>
            <p>HOT SPOT SCHEME</p>
            <hr/>
        </div>
        <div class="contentCenter">
            <div class="partOnelef">
                <div class="partOnelefTop">
                    <img src="/Public/APP/img/partOneleftimg.png"/>
                </div>
                <div class="partOnelefBot partOneBot">
                    <p class="partOneTilTex">个税宝最佳方案</p>
                    <p class="partOneTexTex lineH">1、应对企业自身业务特点，量身制定专属个税筹划;</p>
                    <p class="partOneTexTex lineH">2、优惠返税和个企核定征收，双重优惠结合使用;</p>
                    <p class="partOneTexTex lineH">3、综合平衡业务的合理性、个人收入合法性、个税的风险性。</p>
                    <a href="javascript:;"><img src="/Public/APP/img/partOneIcon.png"></a>
                </div>
            </div>
            <!--遮罩-->
            <div class="partOnefade partFade">
                <p class="partFadeT">个税宝</p>
                <p class="partFadeP">改变个税行为，转移个税风险；</p>
                <p class="partFadeP">平衡个税安全，获得最大收益；</p>
                <p class="partFadeP">综合税率从20%~45%降低到7%~10%。</p>
                <p class="fontFadeClo">个税宝最佳方案税的风险性</p>
                <p class="partFadeP">1、应对企业自身业务特点，量身制定专属个税筹划;</p>
                <p class="partFadeP">2、优惠返税和个企核定征收，双重优惠结合使用;</p>
                <p class="partFadeP">3、综合平衡业务的合理性、个人收入业务的合理性、个人收入</p>
            </div>

            <div class="partOnerig">
                <div class="partOnerigTop">
                    <img src="/Public/APP/img/partOnerighttimg.png"/>
                </div>
                <div class="partOnerigBot partOneBot">
                    <p class="partOneTilTex">节税宝提供三种节税规划服务</p>
                    <p class="partOneTexTex lineH">1、方案课程：节税有方&nbsp;&nbsp;&nbsp; 学习原理;</p>
                    <p class="partOneTexTex lineH">2、上门定制：专家诊断&nbsp;&nbsp;&nbsp; 量身定制;</p>
                    <p class="partOneTexTex lineH">3、节税公司：税收洼地&nbsp;&nbsp;&nbsp; 享受政策。</p>
                    <a href="javascript:;"><img src="/Public/APP/img/partOneIcon.png"></a>
                </div>
            </div>

            <!--遮罩-->
            <div class="partTwofade partFade">
                <p class="partFadeT">扁鹊财院——节税宝</p>
                <p class="partFadeP">降税负：降低企业与个人整体税负</p>
                <p class="partFadeP">少占用：延迟纳税、降低资金成本</p>
                <p class="partFadeP">降风险：降低企业纳税风险</p>
                <p class="fontFadeClo">节税宝提供三种节税规划服务</p>
                <p class="partFadeP">1、方案课程：节税有方   学习原理</p>
                <p class="partFadeP">2、上门定制：专家诊断   量身定制</p>
                <p class="partFadeP">3、节税公司：税收洼地   享受政策</p>
            </div>

            <div style="clear: both;"></div>
        </div>
    </div>

    <div class="partTwo">
        <div class="partTil">
            <h2>近期课程安排</h2>
            <p>RECENT CURRICULUM</p>
            <hr/>
        </div>
        <div class="partTwoTex">
            <div class="contentCenter">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <th>
                    <td>课程</td>
                    <td>地点</td>
                    <td>马上咨询</td>
                    </th>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 10%;" rowspan="2" class="rowSpan">4月
                            <img src="/Public/APP/img/partTwoTabIcon.png"/>
                        </td>
                        <td style="width:20%;">重塑老板财务思维</td>
                        <td style="width:60%;">
                            <p>北京 成都 太原 深圳 义乌 青岛 杭州 广州 长沙 银川 天津 武汉 南京 重庆 徐州 大连 </p>
                        </td>
                        <td style="width:10%;"><a href="">马上咨询</a></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">财务通</td>
                        <td style="width:60%;">
                            <p>北京 成都 太原 深圳 义乌 青岛 杭州 广州 长沙 银川 天津 武汉 南京 重庆 徐州 大连 </p>
                        </td>
                        <td style="width:10%;"><a href="">马上咨询</a></td>
                    </tr>
                    <tr>
                        <td style="width:10%;" rowspan="2" class="rowSpan">5月
                            <img src="/Public/APP/img/partTwoTabIcon.png"/>
                        </td>
                        <td style="width:20%;">财务系统班</td>
                        <td style="width:60%;">
                            <p>北京 成都 太原 深圳 义乌 青岛 杭州 广州 长沙 银川 天津 武汉 南京 重庆 徐州 大连 </p>
                        </td>
                        <td style="width:10%;"><a href="">马上咨询</a></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">税务稽查与应对策略</td>
                        <td style="width:60%;">
                            <p>北京 成都 太原 深圳 义乌 青岛 杭州 广州 长沙 银川 天津 武汉 南京 重庆 徐州 大连 </p>
                        </td>
                        <td style="width:10%;"><a href="">马上咨询</a></td>
                    </tr>
                    <tr>
                        <td style="width:10%;" rowspan="2" class="rowSpan">6月
                            <img src="/Public/APP/img/partTwoTabIcon.png"/>
                        </td>
                        <td style="width:20%;">财务通</td>
                        <td style="width:60%;">
                            <p>北京 成都 太原 深圳 义乌 青岛 杭州 广州 长沙 银川 天津 武汉 南京 重庆 徐州 大连 </p>
                        </td>
                        <td width="10%"><a href="">马上咨询</a></td>
                    </tr>
                    <tr>
                        <td style="width:20%;">财务通</td>
                        <td style="width:60%;">
                            <p>北京 成都 太原 深圳 义乌 青岛 杭州 广州 长沙 银川 天津 武汉 南京 重庆 徐州 大连 </p>
                        </td>
                        <td style="width:10%;"><a href="">马上咨询</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="partThree">
        <div class="partTil">
            <h2>为什么选择我们</h2>
            <p>WHY CHOOSE US?</p>
            <hr/>
        </div>
        <div class="partTreeTex">
            <div class="partThreeTe2">
                <div class="contantCenterOther">
                    <div class="partThreeTe2Lef">
                        <div class="partTreeTe1Se">
                            <img src="/Public/APP/img/partThreePic1.png"/>
                        </div>
                        <div class="partTreeTe1Se">
                            <img src="/Public/APP/img/partThreePic2.png"/>
                        </div>
                        <div class="partTreeTe1Se">
                            <img src="/Public/APP/img/partThreePic3.png"/>
                        </div>

                    </div>
                    <div class="partThreeTe2Rig">
                        <img src="/Public/APP/img/partThreeRigImg.png"/>
                        <div class="partThreeTe2RigTex">
                            <p class="partThreeAT">财务规划体系对企业财务管理有何帮助？</p>
                            <div>
                                <p class="partThreeT">1、规避风险</p>
                                <p class="partThreeTT">通过完善财务制度，优化财务流程，降低老板个人风险，规避企业经营风险，实现财富安全。</p>
                            </div>
                            <div>
                                <p class="partThreeT">2、降低成本</p>
                                <p class="partThreeTT">通过先进的财务管理手段，充分发挥财务的监督管理控制职能，减少成本费用的不合理支出。</p>
                            </div>
                            <div>
                                <p class="partThreeT">3、提升效率</p>
                                <p class="partThreeTT">通过财务管理优化企业经营模式，增收节支，进一步提高企业经营效率。</p>
                            </div>
                            <div>
                                <p class="partThreeT">4、战略依据</p>
                                <p class="partThreeTT">以真实，准确的财务数据为基础，通过财务分析，为企业的经营决策，战略制定提供可靠的数据支持。</p>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="partFour">
        <div class="partTil">
            <h2>企业陪伴计划</h2>
            <p>ENTERPRISE COMPANION PROGRAM</p>
            <hr/>
        </div>
        <div class="partFourTe">
            <div class="contentCenter">
                <div class="partFourTeBlock">
                    <p class="partFourTeBlockTitle">账钱税系统建设</p>
                    <div class="partFourTeBlockText">
                        <img src="/Public/APP/img/partFourcase.png"/>
                        <p>针对企业财务管理问题，提供驻场调研、诊断、方案设计、协助落地等升级服务。</p>
                        <button>点击了解详情</button>
                    </div>
                </div>
                <div class="partFourTeBlock">
                    <p class="partFourTeBlockTitle">预算系统建设</p>
                    <div class="partFourTeBlockText">
                        <img src="/Public/APP/img/partFourcase2.png"/>
                        <p>为企业打造战略目标梳理及数字化，利润与成本控制目标设计。</p>
                        <button>点击了解详情</button>
                    </div>
                </div>
                <div class="partFourTeBlock">
                    <p class="partFourTeBlockTitle">新三板上市服务</p>
                    <div class="partFourTeBlockText">
                        <img src="/Public/APP/img/partFourcase3.png"/>
                        <p>上市专家、法律专家、财务专家进驻企业，提供系统的上市解决方案。</p>
                        <button>点击了解详情</button>
                    </div>
                </div>
                <div class="partFourTeBlock">
                    <p class="partFourTeBlockTitle">企业并购</p>
                    <div class="partFourTeBlockText">
                        <img src="/Public/APP/img/partFourcase4.png"/>
                        <p>国内知名企业财务专家、上市并购专家为企业出售控股权，为企业并购提供一站式服务。</p>
                        <button>点击了解详情</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="partFive">
        <div class="partTil">
            <h2>财税资讯</h2>
            <p>FISCAL INFORMATION</p>
            <hr/>
        </div>
        <div class="partFiveTe">
            <div class="contentCenter">
                <div class="partFiveL">
                    <img src="/Public/APP/img/partFiveLimg.png"/>
                </div>
                <div class="partFiveR">
                    <ul>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                        <li><a href="">完善财务系统，为企业创造财务利润</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</comment>

<footer>
    <div class="contentCenter">
        <div class="footerTil">
            <p class="fontCop">Contect <span>Us</span></p>
            <p class="fontUnder">联系我们</p>
        </div>
        <div class="footerTe">
            <div class="footerL">
                <div class="footerLB">
                    <img src="/Public/APP/img/footerIcon1.png"/>
                    <p>公司地址：北京市朝阳区国贸旺座中心东塔2716室</p>
                </div>
                <div class="footerLB">
                    <img src="/Public/APP/img/footerIcon2.png"/>
                    <p>客户服务：010-5945-8017</p>
                </div>
                <div class="footerLB">
                    <img src="/Public/APP/img/footerIcon3.png"/>
                    <p>版权所有：www.bianquecaiyuan.com</p>
                </div>
            </div>
            <div class="footerR">
                <img src="/Public/APP/img/footerERWEIMA.png"/>
                <p>扫码关注</p>
            </div>
        </div>
    </div>
</footer>
</body>
<script type="text/javascript" src="/Public/APP/js/jquery.min.js"></script>
<script>
    $(function () {
        /*遮罩*/
        $('.partOnelefBot a').click(function () {
            $('.partOnefade').fadeIn(600)
        })
        $('.partOnefade').mouseleave(function () {
            $('.partOnefade').fadeOut(600)
        })
        $('.partOnerigBot a').click(function () {
            $('.partTwofade').fadeIn(600)
        })
        $('.partTwofade').mouseleave(function () {
            $('.partTwofade').fadeOut(600)
        })
        /*表格*/
        $('table td p').addClass('tdP');

        function removeC(e) {
            e.css({
                "height": '1rem',
                'line-height': '0.5rem',
            }).removeClass('tdP');
        }

        function addC(e) {
            e.css({
                "height": '0.54rem',
            }).addClass('tdP');
        }

        $('.rowSpan').click(function () {
            var that = $(this).parent('tr').find('td p');
            var thatr = $(this).parent('tr').next().find('td p');
            if (that.hasClass('tdP') || thatr.hasClass('tdP')) {
                removeC(that);
                removeC(thatr);
            } else {
                addC(that);
                addC(thatr);
            }

        })


    })
</script>
</html>