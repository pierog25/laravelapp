
export default class FileManager{

    constructor(fileResponsed){

        if(fileResponsed !== null && fileResponsed !== undefined){

            this.id = fileResponsed.id;
            this.url = fileResponsed.url;
            this.name = fileResponsed.name;
            this.size = fileResponsed.size;
            this.type = fileResponsed.type;
            this.file = fileResponsed.file;
        } else {
            this.id = undefined;
            this.url = undefined;
            this.name = undefined;
            this.type = undefined;
            this.file = undefined;
        }
    }

    static fromFile(file){

        if(file === null || file === undefined){
            return new FileManager();
        }

        const fileResponsed = {};
        fileResponsed.id = undefined;
        fileResponsed.name = file.name;
        fileResponsed.size = file.size;
        fileResponsed.url = URL.createObjectURL(file);
        fileResponsed.file = file;

        const partsOfName = file.name.split('.');
        fileResponsed.type = partsOfName[partsOfName.length - 1];

        return new FileManager(fileResponsed);
    }

    /*
    async sendFile(fileSend){

            const file = fileSend.file;
            const body = new FormData();
            body.append('files',file);
            try{
                const response = await axios.post(fileSend.url, body);
                if(response.status){
                    return response;
                }
            } catch (e) {
            }
    }
    */
    async createFile(){
        let response = await fetch(this.url);
        let data = await response.blob();
        // let metadata = {
        //     type: 'image/jpeg'
        // };
        let file = new File([data], this.name);
        return file;
    }

    static fromArrayResponse(filesResponsed){
        if(filesResponsed === null || filesResponsed === undefined){
            return undefined;
        }

        const FilesManager = filesResponsed.map((fileResponsed) => {
            return new FileManager(fileResponsed);
        });

        return FilesManager;
    }



}