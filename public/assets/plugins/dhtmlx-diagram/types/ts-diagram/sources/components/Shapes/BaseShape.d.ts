import { ICoords, IItemConfig, ILinkConfig, IBaseShape, BaseType } from "../../../../ts-diagram";
import { BaseComponents } from "../BaseComponents";
export declare class BaseShape extends BaseComponents implements IBaseShape {
    constructor(config: IItemConfig | ILinkConfig, params?: any);
    getBaseType(): BaseType;
    getCenter(): ICoords;
    getPoint(x: number, y: number): ICoords;
}
