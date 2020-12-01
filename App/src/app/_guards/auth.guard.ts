import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { AuthenticationService } from '../_services';

@Injectable()
export class AuthGuard implements CanActivate {

    constructor(private router: Router, private authService: AuthenticationService, ) { }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (localStorage.getItem('currentUser')) {
            // logged in so return true
            this.authService.checkToken()
                .pipe()
                .subscribe(
                    data => {
                        if (data["error"]) {
                            localStorage.removeItem('currentUser');
                            this.router.navigate(['/login'], { queryParams: { returnUrl: state.url } });
                            return false;
                        }
                        else {
                            return true;
                        }
                    },
                    error => {
                        localStorage.removeItem('currentUser');
                        this.router.navigate(['/login'], { queryParams: { returnUrl: state.url } });
                        return false;
                    });

            return true;
        }

        // not logged in so redirect to login page with the return url
        this.router.navigate(['/login'], { queryParams: { returnUrl: state.url } });
        return false;
    }
}