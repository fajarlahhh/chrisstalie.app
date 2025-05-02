export { SelectionEvents } from "../../ts-common/types";
export { DataEvents } from "../../ts-data";
import { VNode } from "../../ts-common/dom";
import { IEventSystem } from "../../ts-common/events";
import { Id, SelectionEvents } from "../../ts-common/types";
import { DataEvents, IDataConfig, IDataItem } from "../../ts-data";
import { BaseShape } from "./components/Shapes/BaseShape";
import { CellManager, CellManagerEvents, ICellManagerHandlersMap } from "./helpers/CellManager";
import { EditorEvents, IEditor } from "./helpers/Editor";
import { Exporter } from "./helpers/Export";
import { Selection } from "./helpers/Selection";
import { ShapesCollection } from "./ShapesCollection";
import { Toolbar } from "./Toolbar";
export declare type OrgTypes = "org" | "mindmap";
export declare type DiagramTypes = OrgTypes | "default";
export declare type DirTypes = "vertical" | "verticalLeft" | "verticalRight";
export declare type BaseType = "base" | "shape" | "line" | "group" | "swimlane";
export declare type BaseDirection = "top" | "bottom" | "left" | "right";
export declare type ActionDirection = "up" | "down" | "left" | "right";
export declare type ActionValidate = "move" | "remove" | "add";
export interface IBaseConfig {
    id?: Id;
    type?: string;
    x?: number;
    y?: number;
    width?: number;
    height?: number;
    fixed?: boolean;
    $group?: Id;
    $gx?: number;
    $gy?: number;
    [key: string]: any;
}
export interface IBaseComponent {
    config: IBaseConfig;
    id: Id;
    isFixed(): boolean;
    getBaseType(): BaseType;
    canResize(): boolean;
    getBox(): IBoxSize;
    render(): VNode;
    getEditorNode(): VNode;
    getMetaInfo(): {
        [key: string]: string;
    }[];
    setEditorNode(editor: VNode): void;
    isEditable(subheaderId?: string): boolean;
    destructor(): void;
}
export interface IBaseShape extends IBaseComponent {
    config: IItemConfig;
    getPoint(x: number, y: number): ICoords;
    getCenter(): ICoords;
}
export interface IGroupStyle {
    strokeWidth?: string | number;
    stroke?: string;
    borderStyle?: string;
    fill?: string;
    overFill?: string;
    partiallyFill?: string;
}
export interface IGroupHeader {
    height?: number | string;
    fill?: string;
    text?: string;
    fontSize?: number | string;
    lineHeight?: number | string;
    textAlign?: textAlign;
    textVerticalAlign?: textVerticalAlign;
    fontStyle?: fontStyle;
    fontColor?: string;
    fontWeight?: string;
    iconColor?: string;
    position?: BaseDirection;
    editable?: boolean;
    closable?: boolean;
    enable?: boolean;
}
export interface IEntryArea {
    catchArea: number;
    borderFlexible: boolean;
}
export interface IExitArea {
    groupBehavior: "unbound" | "boundNoBorderExtension" | "boundBorderExtension";
    padding?: number;
}
export interface IBaseGroupConfig extends IBaseConfig {
    groupChildren?: Id[];
    style?: IGroupStyle;
    header?: IGroupHeader;
    entryArea?: IEntryArea;
    exitArea?: IExitArea;
    open?: boolean;
    $item?: IGroup;
    $minBox?: IBoxSize;
    $editable?: boolean;
    $height?: number;
    $width?: number;
    $captureArea?: "over" | "out" | "partially";
}
export interface IGroupConfig extends IBaseGroupConfig {
    type: "$group";
}
export interface IGroup extends IBaseGroup {
    config: IGroupConfig;
}
export interface IBaseGroup extends IBaseComponent {
    isLocate(shape: IBoxCoords): boolean;
    getLocatePercent(box: IBoxSize): number;
    trackChildMove(box: IBoxSize, mov: IBaseCoords): boolean;
    getChildBox(): IBoxSize;
    setMinBox(box?: IBoxSize): void;
}
export interface IGroupSwimlaneConfig extends IBaseGroupConfig {
    type: "$sgroup";
    $borderPosition?: {
        left: boolean;
        top: boolean;
    };
    $rowCount?: number;
    $colCount?: number;
}
export interface IGroupSwimlane extends IBaseGroup {
    config: IGroupSwimlaneConfig;
}
export interface IBaseSubHeaderConfig {
    fill?: string;
    fontSize?: number | string;
    lineHeight?: number | string;
    textAlign?: textAlign;
    textVerticalAlign?: textVerticalAlign;
    fontStyle?: fontStyle;
    fontColor?: string;
    fontWeight?: string;
    iconColor?: string;
    editable?: boolean;
}
export interface ISubHeaderConfig extends IBaseSubHeaderConfig {
    id?: string;
    text: string;
    $subMenu?: ISubMenuConfig;
    $movePermit?: boolean;
}
export interface ISubHeaderConfigRows extends IBaseSubHeaderConfig {
    height?: number | string;
    position?: "left" | "right";
    enable?: boolean;
    headers?: ISubHeaderConfig[];
}
export interface ISubHeaderConfigCols extends IBaseSubHeaderConfig {
    height?: number | string;
    position?: "top" | "bottom";
    enable?: boolean;
    headers?: ISubHeaderConfig[];
}
export interface ISubMenuConfig {
    enable: boolean;
    data: IDataItem[];
}
export interface ISwimlaneConfig extends IBaseGroupConfig {
    type: "$swimlane";
    layout: Id[][];
    subHeaderRows?: ISubHeaderConfigRows;
    subHeaderCols?: ISubHeaderConfigCols;
}
export interface ISwimlane extends IBaseGroup {
    config: ISwimlaneConfig;
    isEditable(subheaderId: string): boolean;
}
export declare type ICellType = "row" | "col";
export interface IShapeCollectionConfig extends IDataConfig {
    diagramConfig: IDiagramConfig;
}
declare type textAlign = "left" | "center" | "right";
declare type textVerticalAlign = "top" | "center" | "bottom";
declare type strokeType = "line" | "dash" | any;
declare type arrows = "filled" | any;
declare type connectType = "straight" | "flex" | "elbow" | "curved";
declare type fontStyle = "normal" | "italic" | "oblique";
export interface IShapeMap {
    [type: string]: typeof BaseShape;
}
export interface IPreview {
    img?: string;
    scale?: number;
    width?: number | string;
    height?: number | string;
    gap?: number | string;
}
export interface IBaseShapeConfig extends IBaseConfig {
    css?: string;
    dir?: DirTypes;
    from?: string;
    to?: string;
    parent?: string;
    points?: ICoords[];
    angle?: number;
    dx?: number;
    dy?: number;
    hidden?: boolean;
    open?: boolean;
    openDir?: {
        left?: boolean;
        right?: boolean;
    };
    preview?: string | IPreview;
    editable?: boolean;
    $editable?: boolean;
    $count?: number;
    $connection?: string[][];
    $selected?: boolean;
    $blockSelected?: boolean;
    $expandColor?: string;
    $parent?: string;
    $move?: boolean;
    $connectMode?: boolean;
    $level?: number;
    $item?: IBaseShape;
}
export interface IFlowShapeConfig extends IBaseShapeConfig {
    fill?: string;
    text?: string;
    fontColor?: string;
    fontStyle?: fontStyle;
    fontWeight?: string;
    fontSize?: number;
    textAlign?: textAlign;
    textVerticalAlign?: textVerticalAlign;
    lineHeight?: number;
    stroke?: string;
    extraLinesStroke?: string;
    strokeWidth?: number;
    strokeType?: strokeType;
    strokeDash?: string;
}
export interface ICustomShapeConfig {
    [key: string]: any;
}
export interface IFlowShapeTextConfig extends IBaseShapeConfig {
    backgroundColor?: string;
    text?: string;
    fontColor?: string;
    fontStyle?: fontStyle;
    fontWeight?: string;
    fontSize?: number;
    lineHeight?: number;
    textAlign?: textAlign;
    textVerticalAlign?: textVerticalAlign;
}
export interface IOrgChartConfig extends IBaseShapeConfig {
    title?: string;
    text?: string;
    img?: string;
    headerColor?: string;
    parent?: string;
}
export declare type IItemConfig = IFlowShapeConfig | IOrgChartConfig | IFlowShapeTextConfig | ICustomShapeConfig | ILinkConfig;
export declare type IShapeSide = "top" | "bottom" | "right" | "left" | "center" | string;
export interface ILinkConfig extends IBaseShapeConfig {
    type?: strokeType;
    arrow?: string;
    backArrow?: arrows;
    forwardArrow?: arrows;
    fromSide?: IShapeSide;
    toSide?: IShapeSide;
    cornersRadius?: number;
    connectType?: connectType;
    strokeWidth?: number;
    strokeType?: strokeType;
    stroke?: string;
    $selectedPoint?: string;
    customGap?: number;
}
export interface IShapeToolbarConfig {
    content: string | VNode;
    id: string;
    check?: Function;
    css?: Function;
}
export interface IDiagramMargin {
    x?: number;
    y?: number;
    itemX?: number;
    itemY?: number;
}
export interface IDefaultShapeConfig {
    [type: string]: IItemConfig;
}
export interface IAutoPlacement {
    itemPadding?: number;
    levelPadding?: number;
    wide?: string;
    direction?: string;
    graphPadding?: number;
    mode?: "direct" | "edges";
    root: Id;
}
export declare type TreeDir = "right" | "left";
export interface IMindMapConfig {
    direction?: "left" | "right";
    side?: {
        left?: string[];
        right?: string[];
    };
}
export interface IDiagramConfig {
    type?: DiagramTypes;
    typeConfig?: IMindMapConfig;
    defaultShapeType?: string;
    defaultLinkType?: string;
    margin?: IDiagramMargin;
    toolbar?: IShapeToolbarConfig[];
    defaults?: IDefaultShapeConfig;
    properties?: {
        [key: string]: string;
    }[];
    select?: boolean;
    scale?: number;
    scroll?: boolean;
    dragMode?: boolean;
    showGrid?: boolean;
    gridStep?: number;
    lineGap?: number;
    autoplacement?: IAutoPlacement;
    exportStyles?: boolean | string[];
    $svg?: any;
}
export interface IBoxCoords extends IBaseCoords {
    width: number;
    height: number;
}
export interface IBoxSize {
    left: number;
    right: number;
    top: number;
    bottom: number;
}
export interface IDiagramCustomShape extends IBaseShape {
    shapes?: any;
}
export interface IBaseCoords {
    x: number;
    y: number;
}
export interface ICoords extends IBaseCoords {
    side?: string;
    $custom?: boolean;
    $rx?: number;
    $ry?: number;
}
export declare type FilterHandler = (item: IItemConfig) => boolean;
export interface IDiagram {
    version: string;
    events: IEventSystem<DataEvents | SelectionEvents | DiagramEvents | EditorEvents | CellManagerEvents, IDiagramEventHandlersMap | ICellManagerHandlersMap>;
    data: ShapesCollection;
    config: IDiagramConfig;
    selection: Selection;
    cellManager: CellManager;
    export: Exporter;
    toolbar: Toolbar;
    editor: IEditor;
    paint(): any;
    locate(event: Event): IBaseShape;
    collapseItem(id: string, dir?: TreeDir): void;
    expandItem(id: string, dir?: TreeDir): void;
    getScrollState(): ICoords;
    scrollTo(x: number, y: number): void;
    showItem(id: string): void;
    addShape(type: string, parameters: ICustomShapeParam): void;
    autoPlace(config?: IAutoPlacement): void;
}
export interface ICustomShapeParam {
    defaults?: IItemConfig;
    properties?: any[];
    template: (config: ICustomShapeConfig) => string;
    eventHandlers?: {
        [key: string]: any;
    };
}
export interface IContent {
    shapes?: string;
    width?: number;
    height?: number;
    minX?: number;
    minY?: number;
    gap?: number;
    container?: any;
    root?: string;
    scroll?: any;
}
export interface IContentSize {
    x: number;
    y: number;
    left: number;
    top: number;
    scale: number;
}
export declare type SelectShape = (item: IItemConfig) => boolean;
export declare type ActionShape = (item: IItemConfig) => void;
export declare enum DiagramEvents {
    scroll = "scroll",
    beforeRender = "beforerender",
    emptyAreaClick = "emptyAreaClick",
    emptyAreaMouseDown = "emptyAreaMouseDown",
    beforeSubmenuOpen = "beforeSubmenuOpen",
    afterSubmenuOpen = "afterSubmenuOpen",
    beforeCollapse = "beforeCollapse",
    afterCollapse = "afterCollapse",
    beforeExpand = "beforeExpand",
    afterExpand = "afterExpand",
    shapeMouseDown = "shapeMouseDown",
    shapeClick = "shapeClick",
    shapeDblClick = "shapeDblClick",
    shapeIconClick = "shapeIconClick",
    lineMouseDown = "lineMouseDown",
    lineClick = "lineClick",
    lineDblClick = "lineDblClick",
    groupMouseDown = "groupMouseDown",
    groupClick = "groupClick",
    groupDblClick = "groupDblClick",
    groupHeaderClick = "groupHeaderClick",
    groupHeaderDblClick = "groupHeaderDblClick",
    itemMouseDown = "itemMouseDown",
    itemClick = "itemClick",
    itemDblClick = "itemDblClick",
    itemMouseOver = "itemMouseOver",
    itemMouseOut = "itemMouseOut",
    /** @deprecated See a documentation: https://docs.dhtmlx.com/ */
    shapeHover = "shapeHover"
}
export interface IDiagramEventHandlersMap {
    [key: string]: (...args: any[]) => any;
    [DiagramEvents.scroll]: (position: ICoords) => void;
    [DiagramEvents.beforeRender]: (size: IContentSize) => void;
    [DiagramEvents.emptyAreaClick]: (event: MouseEvent) => void;
    [DiagramEvents.shapeIconClick]: (id: string, event: MouseEvent) => void;
    [DiagramEvents.beforeSubmenuOpen]: (id: Id, event: MouseEvent, subHeaderId?: string) => boolean | void;
    [DiagramEvents.afterSubmenuOpen]: (id: Id, event: MouseEvent, subHeaderId?: string) => void;
    [DiagramEvents.beforeCollapse]: (id: Id, dir?: TreeDir) => boolean | void;
    [DiagramEvents.afterCollapse]: (id: Id, dir?: TreeDir) => void;
    [DiagramEvents.beforeExpand]: (id: Id, dir?: TreeDir) => boolean | void;
    [DiagramEvents.afterExpand]: (id: Id, dir?: TreeDir) => void;
    [DiagramEvents.shapeMouseDown]: (id: Id, event: MouseEvent, position?: ICoords) => void;
    [DiagramEvents.shapeClick]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.shapeDblClick]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.lineMouseDown]: (id: Id, event: MouseEvent, position?: ICoords) => void;
    [DiagramEvents.lineClick]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.lineDblClick]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.groupMouseDown]: (id: Id, event: MouseEvent, position?: ICoords) => void;
    [DiagramEvents.groupClick]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.groupDblClick]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.groupHeaderClick]: (id: Id, event: MouseEvent, subHeaderId?: string) => void;
    [DiagramEvents.groupHeaderDblClick]: (id: Id, event: MouseEvent, subHeaderId?: string) => void;
    [DiagramEvents.itemMouseDown]: (id: Id, event: MouseEvent, position?: ICoords) => void;
    [DiagramEvents.itemClick]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.itemDblClick]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.itemMouseOver]: (id: Id, event: MouseEvent) => void;
    [DiagramEvents.itemMouseOut]: (id: Id, event: MouseEvent) => void;
    /** @deprecated See a documentation: https://docs.dhtmlx.com/ */
    [DiagramEvents.shapeHover]: (id: Id, event: MouseEvent) => void;
}
