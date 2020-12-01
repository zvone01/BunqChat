import { Component, OnInit } from '@angular/core';
import { MessageService } from '../_services';
import { User } from '../_models';
import {Router} from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']

})
export class HomeComponent {
  users: User[];
  messages: any[];

  constructor(protected messageService: MessageService, private router: Router) {}

  ngOnInit(): void {
    this.getChatList();
  }

  getChatList() {
    this.users = [];
    this.messageService.getChats().subscribe( x=> {
      console.log(x)
      x['response'].forEach(element => {
        this.users.push({
          id: element['id'],
          name: element['name'],
          password: ''
        })
      });
    });
  }

  goTo(id) {
    console.log(id);
    this.router.navigate(['/message/', id]);
  }
}
