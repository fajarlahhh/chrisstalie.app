import { VNode } from "../../../../ts-common/dom";
import { ShapesCollection } from "../../../../ts-diagram";
import { IFlowShapeTextConfig, IBaseShape } from "../../../../ts-diagram";
import { BaseShape } from "./BaseShape";
export declare class TextShape extends BaseShape implements IBaseShape {
    config: IFlowShapeTextConfig;
    data: ShapesCollection;
    private _oldText;
    constructor(config: IFlowShapeTextConfig, parameters?: any);
    getMetaInfo(): {
        [key: string]: string;
    }[];
    render(): VNode;
    protected setDefaults(config: IFlowShapeTextConfig, defaults: IFlowShapeTextConfig): IFlowShapeTextConfig;
    protected getContent(): VNode;
}
