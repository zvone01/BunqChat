import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators, FormGroup } from '@angular/forms';
import { AuthenticationService } from '../_services';
import { NbToastrService, NbComponentStatus } from '@nebular/theme';
import { first } from 'rxjs/internal/operators/first';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  public Username: string
  loginForm: FormGroup;

  constructor(private formBuilder: FormBuilder,
    private authenticationService: AuthenticationService,
    private toastrService: NbToastrService,
    private router: Router) { }

  ngOnInit() {

    this.resetForm();
  }

  resetForm() {
    this.loginForm = this.formBuilder.group({
      username: [null, Validators.required],
      password: [null, Validators.required],
    });
  }
 openSnackBar(message: string, status: NbComponentStatus) {
    this.toastrService.show(message, `Status:`, { status });
  }

  get f() { return this.loginForm.controls; }

  onSubmit() {
    // this.submitted = true;
    if (this.loginForm.invalid) {
      this.openSnackBar("Form error",'warning')
      return;
    }



    console.log(this.loginForm.value);
    this.authenticationService.login(this.f.username.value, this.f.password.value)
      .pipe(first())
      .subscribe(

        data => {
          console.log(data["response"]);
          this.openSnackBar(data["response"], 'info')
          this.resetForm();
          this.router.navigate(['']);

        },
        error => {
          console.log("Error");
          this.openSnackBar("Error",'warning')

        });

  }

  bypass(){

    this.authenticationService.login("user1", "bbbb")
      .pipe(first())
      .subscribe(

        data => {
          this.resetForm();
          this.router.navigate(['']);
          this.openSnackBar("Loged in",'info')

        },
        error => {
          console.log("Error");
          this.openSnackBar("Error",'warning')

        });

}


}
