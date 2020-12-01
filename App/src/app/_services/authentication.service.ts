﻿import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs/operators';

import { environment } from '../../environments/environment';

@Injectable()
export class AuthenticationService {
    constructor(private http: HttpClient) { }

    login(Username: string, Password: string) {
        return this.http.post<any>(`${environment.apiUrl}/login`, { name: Username, password: Password })
            .pipe(map(response => {
                // login successful if there's a jwt token in the response
                if (response && response.token && response.id) {
                    // store user details and jwt token in local storage to keep user logged in between page refreshes
                    localStorage.setItem('currentUser', JSON.stringify(response.token));
                    localStorage.setItem('currentUserID', JSON.stringify(response.id));
                }

                return response;
            }));
    }

    checkToken() {
        return this.http.post(`${environment.apiUrl}/checktoken`,'');
    }

    logout() {
        // remove user from local storage to log user out
        localStorage.removeItem('currentUser');
    }
}