import { VNode } from "../../../../ts-common/dom";
import { BaseType, IBoxSize, ICoords, ILinkConfig } from "../../../../ts-diagram";
import { BaseShape } from "../Shapes/BaseShape";
export declare class Line extends BaseShape {
    config: ILinkConfig;
    constructor(config: ILinkConfig, defaults?: any);
    getBaseType(): BaseType;
    getMetaInfo(): {
        [key: string]: string;
    }[];
    setDefaults(config: ILinkConfig): ILinkConfig;
    getBox(): IBoxSize;
    render(): VNode;
    protected _getType(): string;
    protected _getPoints(): string;
    protected _getStringPoints(): string;
    protected _getCurvedPoints(config: ILinkConfig): string;
    protected _getArrowLine(): any[];
    protected _getArrow(from: ICoords, to: ICoords): any;
}
