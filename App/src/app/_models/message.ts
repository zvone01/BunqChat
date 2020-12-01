export class Message {
    id: number;
    user: string;
    text: string;
    reply: boolean;
    date: Date;

    constructor()
    {
        this.id = -1;
        this.user = '';
        this.text = '';
        this.reply = false;
        this.date = new Date();
    }

}
