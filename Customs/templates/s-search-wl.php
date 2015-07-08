<ion-view view-title="搜索">
   <ion-content>
       <div class="item item-input-inset">
           <label class="item-input-wrapper">
               <input type="text" placeholder="请输入批次编号" style="width: 100%;" id="wlTxt">
           </label>
           <button class="button button-small" ng-click="search($event,'#wlTxt')">
               &nbsp;搜索&nbsp;
           </button>
       </div>
   </ion-content>
</ion-view>