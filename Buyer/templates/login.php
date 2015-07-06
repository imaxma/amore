<ion-view view-title="登录-爱茉莉关务人员" >
    <ion-content class="padding">
        <div class="titleInfo">
            <h4 class="title">爱茉莉通关进度可视化</h4>
            <p>爱茉莉</p>
        </div>
        <div class="list list-inset loginListBox">
            <label class="item item-input">
                <i class="icon icon-user"></i>
                <input type="text" placeholder="请输入工号"  id="username" <?php if(!empty($_COOKIE['username'])) echo "value=".$_COOKIE['username'];?> >
            </label>
            <label class="item item-input">
                <i class="icon icon-lock"></i>
                <input type="password" placeholder="请输入密码"  id="password" <?php if(!empty($_COOKIE['password'])) echo "value=".$_COOKIE['password'];?> >
            </label>
            <label class="item item-input loginToggle">
                <ion-toggle  ng-model="user.remember">
                    <span style="font-size: 10px; display: block;text-align: right;margin-right: -25px;">记住密码？</span>
                </ion-toggle>
            </label>
        </div>
        <button class="button button-block button-calm" ng-click="login(user)">登录</button>

    </ion-content>
    <div class="bar bar-footer bar-dark footerBox" >
        <h3>AMORE PACIFIC<p>Copyright©2015<span>爱茉莉(化妆品)公司</span></p></h3>
    </div>
</ion-view>