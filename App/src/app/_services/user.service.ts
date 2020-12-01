import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';
import { Observable } from 'rxjs';
import {  User } from '../_models';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(private http: HttpClient) { }

  getUser(id: number): Observable<User> {
    return this.http.get<User>(`${environment.apiUrl}/user/`+id);
  }

  create(Name: string, Pass: string) {
    return this.http.post(`${environment.apiUrl}/user`, {name: Name, password: Pass});
  }

  checkToken() {
    return this.http.post(`${environment.apiUrl}/user/checktoken.php`,'');
}


}
