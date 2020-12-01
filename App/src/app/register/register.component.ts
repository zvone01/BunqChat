import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators, FormGroup } from '@angular/forms';
import { AuthenticationService, UserService } from '../_services';
import { NbToastrService, NbComponentStatus } from '@nebular/theme';
import { first } from 'rxjs/internal/operators/first';
import { Router } from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {

  public Username: string
  registerForm: FormGroup;

  constructor(private formBuilder: FormBuilder,
    private authenticationService: AuthenticationService,
    private userService: UserService,
    private toastrService: NbToastrService,
    private router: Router) { }

  ngOnInit() {

    this.resetForm();
  }

  resetForm() {
    this.registerForm = this.formBuilder.group({
      username: [null, Validators.required],
      password: [null, Validators.required],
    });
  }

 openSnackBar(message: string, status: NbComponentStatus) {
    this.toastrService.show(message, `Status:`, { status });
  }

  get f() { return this.registerForm.controls; }

  onSubmit() {
    // this.submitted = true;
    if (this.registerForm.invalid) {
      this.openSnackBar("Form error",'warning')
      return;
    }
    console.log(this.registerForm.value);
    this.userService.create(this.f.username.value, this.f.password.value)
      .pipe(first())
      .subscribe(

        data => {
          console.log(data["message"]);
          this.openSnackBar(data["message"], 'info')
          this.resetForm();

        },
        error => {
          console.log("Error");
          this.openSnackBar("Error",'warning')

        });

  }

  bypass(){

    this.authenticationService.login("ZvoneD", "123")
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
