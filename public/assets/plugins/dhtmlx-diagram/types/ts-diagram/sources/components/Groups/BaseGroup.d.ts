import { VNode } from "../../../../ts-common/dom";
import { IDataItem } from "../../../../ts-data";
import { BaseType, IBaseCoords, IBaseGroup, IBoxCoords, IBoxSize, IBaseGroupConfig } from "../../../../ts-diagram";
import { ShapesCollection } from "../../ShapesCollection";
import { BaseComponents } from "../BaseComponents";
export declare class BaseGroup extends BaseComponents implements IBaseGroup {
    config: IBaseGroupConfig;
    protected data: ShapesCollection;
    constructor(config: IBaseGroupConfig, params: any);
    getBaseType(): BaseType;
    isEditable(subheaderId?: string): boolean;
    getMetaInfo(): {
        [key: string]: string;
    }[];
    isLocate(box: IBoxCoords): boolean;
    getLocatePercent(box: IBoxSize): number;
    setMinBox(box?: IBoxSize): void;
    getBox(): IBoxSize;
    getChildBox(): IBoxSize;
    trackChildMove(box: IBoxSize, mov: IBaseCoords): boolean;
    getChildHtmlNodes(config: IBaseGroupConfig, data: ShapesCollection | IDataItem[]): VNode[];
    getChildSvgNodes({ $typeConfig, x, y, groupChildren, header }: IBaseGroupConfig, data: ShapesCollection): VNode[];
    getGroupHeader(config: IBaseGroupConfig, editorNode: VNode): VNode;
    protected setDefaults(config: IBaseGroupConfig): IBaseGroupConfig;
    protected setServiceProp(config: IBaseGroupConfig): void;
}
