<ion-view view-title="搜索">
    <ion-content>
        <div class="searchBox" id="searchBox">
            <div class="list">
                <ion-radio ng-repeat="item in items"
                           ng-value="item.value"
                           ng-change="change(item)"
                           ng-model="type">
                    ${item.text}
                </ion-radio>
            </div>
            <button class="button button-full button-calm" ng-click="search($event, '#searchBox')">
                搜索
            </button>
        </div>
    </ion-content>
</ion-view>