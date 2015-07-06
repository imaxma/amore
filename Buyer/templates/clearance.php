
<ion-view view-title="通关进度" cache-view="false">
  <ion-content class="padding">

      <div class="deliveryBox">
      <div class="deliveryList block"  ng-repeat="item in items" on-hold="bottomMenu($event,item)">
        <div class="row mt5">
          <p class="de_50">
            <a href="#/user/search_wl"><span style="display: inline-block;width:60px;font-weight:700;">批次编号:</span><span class="ml10">${item.batch}</span></a>
          </p>
          <p class="de_50">
            <a href="#/user/search_order/${item.type}" ><span style="display: inline-block;width:60px;font-weight:700;">类型:</span><span class="ml10">

              <span ng-if="item.type == 'air'">空运</span>
              <span ng-if="item.type == 'bulk'">散货</span>
              <span ng-if="item.type == 'full'">整柜</span>

            </span></a>
          </p>
        </div>
        <div class="row mt5">
          <p class="de_100">
            <a href="#/user/search_gys" >报关公司: <span class="ml10" id="${item.customsid}">${item.customs}</span></a>
          </p>
        </div>
        <div class="row mt5">
          <p class="de_50" ><span style="display: inline-block;width:60px;font-weight:700;">ETD:</span>  <span>${item.etd}</span></p>
          <p class="de_50" ><span style="display: inline-block;width:60px;font-weight:700;">ETA:</span>  <span>${item.eta}</span></p>
        </div>
        <div class="row mt5">
          <ul class="chartUl row" >
            <li ng-class="{'on':item.DE.date}">
              <p class="title">换单</p>
              <p><span>
                    <span ng-if="item.DE.date">${item.DE.date | filterDay}
                        <span ng-if="item.DE.delay > 0"><span style="color:red">(+${item.DE.delay})</span></span>
                        <span ng-if="item.DE.delay < 0"><span style="color:red">(${item.DE.delay})</span></span>
                    </span>
                    <span ng-if="!item.DE.date">${item.DE.standard | filterDay}</span>
                 </span>
                <input class="dateIpt" on-tap="tap($event,item.DE,'DE')" type="text" standard="${item.DE.standard}" delay="${item.DE.delay}" value="${item.DE.date}" autocomplete="off"/></p>
            </li>
            <li  ng-class="{'on':item.AFE.date}">
              <p class="title">报检</p>
              <p><span>
                    <span ng-if="item.AFE.date">${item.AFE.date | filterDay}
                        <span ng-if="item.AFE.delay > 0"><span style="color:red">(+${item.AFE.delay})</span></span>
                        <span ng-if="item.AFE.delay < 0"><span style="color:red">(${item.AFE.delay})</span></span>
                    </span>
                    <span ng-if="!item.AFE.date && !item.AFE.delay">${item.AFE.standard | filterDay}</span>
                 </span>
                <input class="dateIpt" on-tap="tap($event,item.AFE,'AFE')" type="text" standard="${item.AFE.standard}" delay="${item.AFE.delay}" value="${item.AFE.date}" autocomplete="off"/></p>
            </li>
            <li ng-class="{'on':item.DI.date}">
              <p class="title">审单</p>
              <p><span>
                    <span ng-if="item.DI.date">${item.DI.date | filterDay}
                        <span ng-if="item.DI.delay > 0"><span style="color:red">(+${item.DI.delay})</span></span>
                        <span ng-if="item.DI.delay < 0"><span style="color:red">(${item.DI.delay})</span></span>
                    </span>
                    <span ng-if="!item.DI.date && !item.DI.delay">${item.DI.standard | filterDay}</span>
                 </span>
                <input class="dateIpt" on-tap="tap($event,item.DI,'DI')" type="text" standard="${item.DI.standard}" delay="${item.DI.delay}" value="${item.DI.date}" autocomplete="off"/></p>
            </li>
            <li ng-class="{'on':item.TAX.date}">
              <p class="title">付税</p>
              <p><span>
                    <span ng-if="item.TAX.date">${item.TAX.date | filterDay}
                        <span ng-if="item.TAX.delay > 0"><span style="color:red">(+${item.TAX.delay})</span></span>
                        <span ng-if="item.TAX.delay < 0"><span style="color:red">(${item.TAX.delay})</span></span>
                    </span>
                    <span ng-if="!item.TAX.date && !item.TAX.delay">${item.TAX.standard | filterDay}</span>
                 </span>
                <input class="dateIpt" on-tap="tap($event,item.TAX,'TAX')" type="text" standard="${item.TAX.standard}" delay="${item.TAX.delay}" value="${item.TAX.date}" autocomplete="off"/></p>
            </li>
            <li ng-class="{'on':item.Customs.date}">
              <p class="title">报关</p>
              <p><span>
                    <span ng-if="item.Customs.date">${item.Customs.date | filterDay}
                        <span ng-if="item.Customs.delay > 0"><span style="color:red">(+${item.Customs.delay})</span></span>
                        <span ng-if="item.Customs.delay < 0"><span style="color:red">(${item.Customs.delay})</span></span>
                    </span>
                    <span ng-if="!item.Customs.date && !item.Customs.delay">${item.Customs.standard | filterDay}</span>
                 </span>
                <input class="dateIpt" on-tap="tap($event,item.Customs,'Customs')" type="text" standard="${item.Customs.standard}" delay="${item.Customs.delay}" value="${item.Customs.date}" autocomplete="off"/></p>
            </li>
            <li ng-class="{'on':item.Inspection.date}">
              <p class="title">查验</p>
              <p><span>
                    <span ng-if="item.Inspection.date">${item.Inspection.date | filterDay}
                        <span ng-if="item.Inspection.delay > 0"><span style="color:red">(+${item.Inspection.delay})</span></span>
                        <span ng-if="item.Inspection.delay < 0"><span style="color:red">(${item.Inspection.delay})</span></span>
                    </span>
                    <span ng-if="!item.Inspection.date && !item.Inspection.delay">${item.Inspection.standard | filterDay}</span>
                 </span>
                <input class="dateIpt" on-tap="tap($event,item.Inspection,'Inspection')" type="text" standard="${item.Inspection.standard}" delay="${item.Inspection.delay}" value="${item.Inspection.date}" autocomplete="off"/></p>
            </li>
            <li ng-class="{'on':item.Warehouse.date}">
              <p class="title">入库</p>
              <p><span>
                    <span ng-if="item.Warehouse.date">${item.Warehouse.date | filterDay}
                        <span ng-if="item.Warehouse.delay > 0"><span style="color:red">(+${item.Warehouse.delay})</span></span>
                        <span ng-if="item.Warehouse.delay < 0"><span style="color:red">(${item.Warehouse.delay})</span></span>
                    </span>
                    <span ng-if="!item.Warehouse.date && !item.Warehouse.delay">${item.Warehouse.standard | filterDay}</span>
                 </span>
                <input class="dateIpt" on-tap="tap($event,item.Warehouse,'Warehouse')" type="text" standard="${item.Warehouse.standard}" delay="${item.Warehouse.delay}" value="${item.Warehouse.date}" autocomplete="off"/></p>
            </li>
            <li>
              <p class="title"></p>
              <p></p>
            </li>
          </ul>
        </div>

        <div class="row mt5" ng-if=' item.notes  ' >
          <div class="clearanceBz" >${item.notes}   </div>
        </div>

      </div>


    </div>

    <ion-infinite-scroll
            ng-if="moreDataCanBeLoaded()"
            on-infinite="loadMore()"
            distance="1%">
    </ion-infinite-scroll>
    <div class="notDatas" ng-if="isNotData" style="text-align: center;padding:10px 0 ;">
        <span ng-if="items.length > 0">没有更多数据了...</span>
        <span ng-if="items.length <= 0">没有搜索到内容...</span>
    </div>

  </ion-content>
  <div class="muntFixed" ng-click="create()">+</div>
</ion-view>
