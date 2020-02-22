import {Eventable} from "../utils/Eventable";
import {OfflineStore} from "../nfccards/store/OfflineStore";
import {TransactionStore} from "../nfccards/store/TransactionStore";
import {Logger} from "../nfccards/tools/Logger";
import {NfcReader} from "../nfccards/nfc/NfcReader";
import {Camera} from "./Camera";

/**
 *
 */
export class CameraService extends Eventable {

    private axios: any = null;

    private camera: Camera;

    /**
     *
     */
    constructor(
        axios: any
    ) {
        super();

        this.axios = axios;
        this.camera = new Camera();
    }

    /**
     * @param nfcService
     * @param nfcPassword
     */
    public connect(nfcService: string, nfcPassword: string) {
        this.camera.connect(nfcService, nfcPassword);


    }

    public takePicture(name: string) {
        return this.camera.takePicture(name);
    }

}
