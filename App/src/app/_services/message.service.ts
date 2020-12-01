import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';
import { Observable } from 'rxjs';
import { Message } from '../_models';
import { map } from 'rxjs/operators';


@Injectable({
  providedIn: 'root'
})
export class MessageService {

  constructor(private http: HttpClient) { }

  getChats() {
    return this.http.get(`${environment.apiUrl}/message`);
  }

  /**
   *
   * @param msg message that is sent
   * @param userid id of user to whom message will be sent
   */
  create(msg: string, userid: number) {
    return this.http.post(`${environment.apiUrl}/message`, { to_user_id: userid,  message: msg });
  }

  /**
   *
   * @param userId id of user with whom you want to get messages
   */
  readOne(userId: number) {
    return this.http.get(`${environment.apiUrl}/message/${userId}`);
  }

}
