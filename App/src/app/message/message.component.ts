import { Component, OnInit } from '@angular/core';
import { MessageService, UserService } from '../_services';
import { Message, User } from '../_models';
import { ActivatedRoute } from '@angular/router';
// import { ChatShowcaseService } from './chat-showcase.service';

@Component({
  selector: 'app-message',
  templateUrl: './message.component.html',
  styleUrls: ['./message.component.scss'],
  // providers: [ ChatShowcaseService ],
})
export class MessageComponent {

  messages: any[];
  user1: User = new User();
  user2: User = new User();
  constructor(protected messageService: MessageService, protected userService: UserService,
    private route: ActivatedRoute) {

    this.messages = new Array();
  }

  ngOnInit(): void {
    this.getUsers();
    this.getMessages();
  }

  getUsers() {
    this.user1.id = JSON.parse(localStorage.getItem('currentUserID'));
    this.user2.id = parseInt(this.route.snapshot.paramMap.get('user'));;
    this.userService.getUser(this.user1.id).subscribe(x => { this.user1 = x['response'] });
    this.userService.getUser(this.user2.id).subscribe(x => { this.user2 = x['response'] });
  }
  getMessages() {
    this.messageService.readOne(this.user2.id)
    .subscribe( x => {
      console.log(x)
      x['response'].forEach(element => {
        this.messages.push({
          text: element.message,
          date: element.created_at,
          reply: element.from_user_id === this.user1.id ? true : false,
          type: 'text',
          user: {
            name: element.from_user_id === this.user1.id ? this.user1.name : this.user2.name ,
            avatar: 'https://i.gifer.com/no.gif',
          },
        });
      });
    });
  }
  sendMessage(event: any) {
    const files = !event.files ? [] : event.files.map((file) => {
      return {
        url: file.src,
        type: file.type,
        icon: 'file-text-outline',
      };
    });
    this.messageService.create(event.message, this.user2.id)
    .subscribe( x => { console.log(x)}),

    this.messages.push({
      text: event.message,
      date: new Date(),
      reply: true,
      type: files.length ? 'file' : 'text',
      user: {
        name: this.user1.name,
        avatar: 'https://i.gifer.com/no.gif',
      },
    });
   /* const botReply = this.chatShowcaseService.reply(event.message);
    if (botReply) {
      setTimeout(() => { this.messages.push(botReply) }, 500);
    }*/
  }
}
