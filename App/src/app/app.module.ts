
import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';

// login
import { AuthGuard } from './_guards';
import { AuthenticationService} from './_services';
import { JwtInterceptor } from './_helpers';

// services
import {MessageService, UserService} from './_services'


import { ReactiveFormsModule, FormsModule} from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NbToastrModule, NbThemeModule, NbInputModule, NbLayoutModule, NbChatModule, NbSidebarModule, NbButtonModule,NbActionsModule, NbCardModule, NbListModule, NbUserModule  } from '@nebular/theme';
import { NbEvaIconsModule } from '@nebular/eva-icons';
import { HomeComponent } from './home/home.component';
import { LoginComponent } from './login/login.component';
import { MessageComponent } from './message/message.component';
import { RegisterComponent } from './register/register.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    LoginComponent,
    MessageComponent,
    RegisterComponent
  ],
  imports: [
    HttpClientModule,
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    NbThemeModule.forRoot({ name: 'default' }),
    NbLayoutModule,
    NbEvaIconsModule,
    NbChatModule,
    NbListModule,
    NbUserModule,
    NbSidebarModule,
    NbButtonModule,
    NbActionsModule,
    NbInputModule,
    NbCardModule,
    NbToastrModule.forRoot(),
    ReactiveFormsModule,
    FormsModule,
  ],
  providers: [AuthenticationService, AuthGuard,
    MessageService,
    { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true },
    UserService,
    { provide: HTTP_INTERCEPTORS, useClass: JwtInterceptor, multi: true },
    ],
  bootstrap: [AppComponent]
})
export class AppModule { }
