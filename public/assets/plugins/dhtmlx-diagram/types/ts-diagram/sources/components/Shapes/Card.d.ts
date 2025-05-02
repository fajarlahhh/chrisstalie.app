import { VNode } from "../../../../ts-common/dom";
import { IOrgChartConfig, IBaseShape } from "../../../../ts-diagram";
import { BaseShape } from "./BaseShape";
export declare class Card extends BaseShape implements IBaseShape {
    config: IOrgChartConfig;
    constructor(config: IOrgChartConfig, parameters?: any);
    getMetaInfo(): {
        [key: string]: string;
    }[];
    render(): VNode;
    protected setDefaults(config: IOrgChartConfig, defaults?: IOrgChartConfig): IOrgChartConfig;
    protected getContent(): any;
}
