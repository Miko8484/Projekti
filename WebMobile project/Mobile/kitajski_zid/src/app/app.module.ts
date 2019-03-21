import { NgModule, ErrorHandler } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { IonicApp, IonicModule, IonicErrorHandler } from 'ionic-angular';
import { MyApp } from './app.component';
import { HttpModule } from '@angular/http';

import { AboutPage } from '../pages/about/about';
import { TabsPage } from '../pages/tabs/tabs';
import { LoginPage } from '../pages/login/login';
import { NewsPage } from '../pages/news/news';
import { RedditPage } from '../pages/reddit/reddit';
import { UsersPage } from '../pages/users/users';
import { GamePage } from '../pages/game/game';
import { ProfilePage } from '../pages/profile/profile';
import { RegisterPage } from '../pages/register/register';
import { HistoryPage } from '../pages/history/history';

import { StatusBar } from '@ionic-native/status-bar';
import { SplashScreen } from '@ionic-native/splash-screen';

import {LoginApi} from '../providers/login-api';
import { Geolocation } from '@ionic-native/geolocation';
import { Camera } from '@ionic-native/camera';

@NgModule({
  declarations: [
    MyApp,
    AboutPage,
    TabsPage,
    LoginPage,
    NewsPage,
    RedditPage,
    UsersPage,
    GamePage,
    ProfilePage,
    HistoryPage,
    RegisterPage
  ],
  imports: [
    BrowserModule,
    HttpModule,
    IonicModule.forRoot(MyApp)
  ],
  bootstrap: [IonicApp],
  entryComponents: [
    MyApp,
    AboutPage,
    TabsPage,
    LoginPage,
    NewsPage,
    RedditPage,
    UsersPage,
    GamePage,
    ProfilePage,
    HistoryPage,
    RegisterPage
  ],
  providers: [
    StatusBar,
    SplashScreen,
    {provide: ErrorHandler, useClass: IonicErrorHandler},LoginApi,Camera,Geolocation
  ]
})
export class AppModule {}
