<ion-view view-title="搜索">
    <ion-content>
        <div class="item item-input-inset">
            <label class="item-input-wrapper">
                <input type="text" placeholder="请输入报关公司全称或关键词" style="width: 100%;" id="searchTxt">
            </label>
            <button class="button button-small" ng-click="search($event,'#searchTxt')">
                &nbsp;搜索&nbsp;
            </button>
        </div>
        <div class="list" >
            <a class="item" ng-repeat="item in all_gys" ng-click="search($event)" id="${item.id}" >${item.name}</a>
        </div>
    </ion-content>
</ion-view>