<ion-view view-title="登录-报关公司" >
    <ion-content class="padding">
        <div class="titleInfo">
            <h4 class="title">爱茉莉通关进度可视化</h4>
            <p>报关公司</p>
        </div>
        <div class="list list-inset loginListBox">
            <label class="item item-input">
                <i class="icon icon-user"></i>
                <input type="text" placeholder="请输入用户名"  id="username" <?php if(!empty($_COOKIE['username'])) echo "value=".$_COOKIE['username'];?> >
            </label>
            <label class="item item-input">
                <i class="icon icon-lock"></i>
                <input type="password" placeholder="请输入密码"  id="password" <?php if(!empty($_COOKIE['password'])) echo "value=".$_COOKIE['password'];?> >
            </label>
            <div class="loginToggle">
            	<a href="javascript:;" class="findPassword" ng-click="findPassword()">修改密码</a>
                <label class="toggle fr">
				   <input type="checkbox" ng-model="user.remember">
				   <div class="track">
				     <div class="handle"></div>
				   </div>
				</label>
                <span class="fr" style="margin-top:5px;">记住密码？</span>
            </div>
            
        </div>
        <button class="button button-block button-calm" ng-click="login(user)">登录</button>

    </ion-content>
    <div class="bar bar-footer bar-dark footerBox" >
        <h3>AMORE PACIFIC<p>Copyright©2015<span>爱茉莉(化妆品)公司</span></p></h3>
    </div>
</ion-view>