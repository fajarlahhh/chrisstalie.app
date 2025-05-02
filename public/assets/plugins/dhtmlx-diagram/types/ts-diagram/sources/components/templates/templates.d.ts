import { VNode } from "../../../../ts-common/dom";
import { IFlowShapeConfig, IOrgChartConfig, ICustomShapeConfig, IBaseGroupConfig, IBaseCoords, BaseDirection } from "../../../../ts-diagram";
export declare function getCircleTpl(config: IFlowShapeConfig | IOrgChartConfig | ICustomShapeConfig, index?: number): VNode;
export declare function getHeaderTpl(config: IOrgChartConfig): VNode;
export declare function getTextTemplate(config: IOrgChartConfig, content: any): VNode;
export declare function getShapeCss(config: IFlowShapeConfig): {
    [key: string]: string | number;
};
interface IExpandIconConfig {
    open: boolean;
    color: string;
    position: BaseDirection;
}
export declare function getExpandIcon(config: IExpandIconConfig): VNode;
interface IMenuIconConfig {
    dir: "rows" | "cols";
    color: string;
}
export declare function getMenuIcon(config: IMenuIconConfig): VNode;
export declare function getGroupContainerStyle(config: IBaseGroupConfig, coords: IBaseCoords): {
    [key: string]: string | number;
};
export declare function getSwimlaneBackGround(config: IBaseGroupConfig, coords: IBaseCoords): {
    [key: string]: string | number;
};
export declare function getSwimlaneContainerStyle(config: IBaseGroupConfig, coords: IBaseCoords): {
    [key: string]: string | number;
};
export declare const getTextStyle: (config: any) => {
    "justify-content": any;
    "align-items": any;
    fontStyle: any;
    fontWeight: any;
    lineHeight: string;
    fontSize: any;
    color: any;
    width: any;
    height: any;
};
export {};
