export const RoleEnum = Object.freeze({
    ADMIN: 'admin',
    USER: 'user',
    EMPLOYEE: 'employee',

    values() {
        return [this.ADMIN, this.EMPLOYEE, this.USER];
    },
});
