import FileManager from '../utils/file_manager.js';

export default class FileProvider {

    static async fetchFile(fileManager) {

        if(fileManager === undefined || fileManager === null){
            return new FileManager();
        }

        if(fileManager.file === undefined){
            return new FileManager();
        }

        const file = fileManager.file;
        const body = new FormData();

        body.append('files', file)
        try {
            const res = await FileProvider.getData('https://fileserver.shalomcontrol.com/api/file',body)
            if(res){
                await Alerts.showToastMessage('Imagen subida con exito!', 'center-center');
                res['url'] =  'https://fileserver.shalomcontrol.com/file-view/'+res['url']
                return new FileManager(res);
            }
        } catch(err) {
            console.log(err);
        }
    }

    /**
     * Return Array of FileManager
     * @param Array
     * @returns {Promise<Array>}
     */
    static async fetchFiles(filesManager){
        let filesResponsed = [];
        for (let fileManager of filesManager) {
            const fileResponsed = await FileProvider.fetchFile(fileManager);
            filesResponsed.push(fileResponsed);
        }
        return filesResponsed;
    }

    static async getData(ajaxurl,body) {
        return $.ajax({
            url: ajaxurl,
            data: body,
            type:'post',
            contentType: false,
            cache: false,
            processData:false,
        });
    }
}

