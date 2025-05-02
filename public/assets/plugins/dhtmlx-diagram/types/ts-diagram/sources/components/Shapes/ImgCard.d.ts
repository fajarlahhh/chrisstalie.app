import { VNode } from "../../../../ts-common/dom";
import { IOrgChartConfig } from "../../../../ts-diagram";
import { Card } from "./Card";
export declare class ImgCard extends Card {
    getMetaInfo(): {
        [key: string]: string;
    }[];
    protected setDefaults(config: IOrgChartConfig, defaults?: IOrgChartConfig): IOrgChartConfig;
    protected getCss(): string;
    protected getContent(): VNode;
}
