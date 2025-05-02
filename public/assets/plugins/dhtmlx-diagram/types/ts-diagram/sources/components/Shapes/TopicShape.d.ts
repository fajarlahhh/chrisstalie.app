import { VNode } from "../../../../ts-common/dom";
import { ShapesCollection, IBaseShape } from "../../../../ts-diagram";
import { BaseShape } from "./BaseShape";
export declare class TopicShape extends BaseShape implements IBaseShape {
    config: any;
    data: ShapesCollection;
    private _oldText;
    constructor(config: any, parameters?: any);
    getMetaInfo(): {
        [key: string]: string;
    }[];
    render(): VNode;
    protected setDefaults(config: any, defaults: any): any;
}
