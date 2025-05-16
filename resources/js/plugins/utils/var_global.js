export default class VarGlobal {

    constructor() {
        this.urlEmpresarial = ''
        this.urlFileServer = 'https://fileserver.shalomcontrol.com/'
        this.urlPro = 'https://reportmkt.shalom.com.pe/'
    }

    getUrlFileServerApiFile( ){
        return `${this.urlFileServer}api/file`
    }

    getUrlFileServerFileView( ){
        return `${this.urlFileServer}file-view/`
    }

    getUrlPro( ){
        return `${this.urlPro}`
    }

    getUrlProAddPath(  path ){
        return `${this.urlPro+path}`
    }

}