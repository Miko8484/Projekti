import { Component } from '@angular/core';

import { AboutPage } from '../about/about';
import { NewsPage } from '../news/news';
import { RedditPage } from '../reddit/reddit';
import { UsersPage } from '../users/users';
import { GamePage } from '../game/game';
import { HistoryPage } from '../history/history';

@Component({
  templateUrl: 'tabs.html'
})
export class TabsPage {

  tab1Root = AboutPage;
  tab2Root = RedditPage;
  tab3Root = NewsPage;
  tab4Root = UsersPage;
  tab5Root = GamePage;
  tab6Root = HistoryPage;

  constructor() {

  }
}
