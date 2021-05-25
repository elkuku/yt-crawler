import { Controller } from 'stimulus';

export default class extends Controller {
    static values = {
        urlId:String,
        urlInfo:String
    }

    static targets = [
        'id', 'title', 'description'
    ]

    async update(event) {
        let response = await fetch(this.urlIdValue+'?q='+event.target.value);
        let idInfo = await response.json();

        response = await fetch(this.urlInfoValue+'?q='+idInfo.id);
        let info = await response.json();

        this.idTarget.value = info.id
        this.titleTarget.value = info.title
        this.descriptionTarget.value = info.description
    }
}
