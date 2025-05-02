import { IEventSystem } from "../../ts-common/events";
import { Id, SelectionEvents } from "../../ts-common/types";
import { DataEvents, IDataItem } from "../../ts-data";
import { Diagram, DiagramEvents, DiagramTypes, IAutoPlacement, IBaseCoords, IShapeToolbarConfig } from "../../ts-diagram";
import { UndoManager } from "./UndoManager";
export interface IDiagramEditor {
    version: string;
    config: IDefaultEditorConfig | IOrgEditorConfig | IMindmapEditorConfig | IEditorConfig;
    events: IEventSystem<DataEvents | SelectionEvents | DiagramEvents | DiagramEditorEvents, IDiagramEditorHandlersMap>;
    diagram: Diagram;
    history: UndoManager;
    paint(): void;
    import(diagram: Diagram): void;
    parse(data: IDataItem[]): void;
    serialize(): IDataItem[];
}
export interface ITargetOption {
    selected: IDataItem | null;
    exclude?: Id | null;
    coord?: IBaseCoords;
}
export interface IEditorControls {
    apply?: boolean;
    reset?: boolean;
    export?: boolean;
    import?: boolean;
    autoLayout?: boolean;
    historyManager?: boolean;
    editManager?: boolean;
    scale?: boolean;
    gridStep?: boolean;
}
export declare type OrgToolbarTypes = "add" | "horizontal" | "vertical" | "remove";
export declare type MindmapToolbarTypes = "add" | "addLeft" | "addRight" | "remove";
export declare type RadialToolbarTypes = "add" | "remove";
export declare type DefaultToolbarTypes = "copy" | "connect" | "removePoint" | "remove";
export declare type OrgShapeToolbar = IShapeToolbarConfig[] | OrgToolbarTypes[] | boolean[] | (IShapeToolbarConfig | OrgToolbarTypes | boolean)[];
export declare type MindmapShapeToolbar = IShapeToolbarConfig[] | MindmapToolbarTypes[] | boolean[] | (IShapeToolbarConfig | MindmapToolbarTypes | boolean)[];
export declare type RadialShapeToolbar = IShapeToolbarConfig[] | RadialToolbarTypes[] | boolean[] | (IShapeToolbarConfig | RadialToolbarTypes | boolean)[];
export declare type DefaultShapeToolbar = IShapeToolbarConfig[] | DefaultToolbarTypes[] | boolean[] | (IShapeToolbarConfig | DefaultToolbarTypes | boolean)[];
export interface IEditorConfig {
    shapeType?: string;
    type?: DiagramTypes;
    gridStep?: number;
    reservedWidth: number;
    editMode?: boolean;
    lineGap?: number;
    defaults?: any;
    scale?: number;
    controls?: IEditorControls;
}
export interface IOrgEditorConfig extends IEditorConfig {
    type: "org";
    shapeToolbar?: boolean | OrgShapeToolbar;
}
export interface IMindmapEditorConfig extends IEditorConfig {
    type: "mindmap";
    shapeToolbar?: boolean | MindmapShapeToolbar;
}
export interface IDefaultEditorConfig extends IEditorConfig {
    type: "default";
    autoplacement?: IAutoPlacement;
    shapeBarWidth?: number;
    shapeSections?: IShapeSections;
    gapPreview?: string | number;
    scalePreview?: string | number;
    shapeToolbar: boolean | DefaultShapeToolbar;
}
export interface ISelectionBox {
    start: ICoords;
    end: ICoords;
}
export interface IShapeSections {
    [key: string]: any;
}
export interface ICoords {
    x: number;
    y: number;
}
export interface IDataHash {
    [id: string]: string | number | boolean;
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
export interface ISidebarConfig {
    showGridStep?: boolean;
}
export declare enum DiagramEditorEvents {
    resetButton = "resetbutton",
    applyButton = "applybutton",
    undoButton = "undoButton",
    redoButton = "redoButton",
    shapeResize = "shaperesize",
    zoomIn = "zoomin",
    zoomOut = "zoomout",
    visibility = "visibility",
    shapesUp = "shapesup",
    exportData = "exportData",
    importData = "importData",
    blockSelectionFinished = "blockSelectionFinished",
    blockSelectionAreaChanged = "blockSelectionAreaChanged",
    autoLayout = "autoLayout",
    changeGridStep = "changeGridStep",
    beforeShapeIconClick = "beforeShapeIconClick",
    afterShapeIconClick = "afterShapeIconClick",
    beforeShapeMove = "beforeShapeMove",
    afterShapeMove = "afterShapeMove",
    shapeMoveEnd = "shapeMoveEnd",
    beforeGroupMove = "beforeGroupMove",
    afterGroupMove = "afterGroupMove",
    groupMoveEnd = "groupMoveEnd",
    beforeItemMove = "beforeItemMove",
    afterItemMove = "afterItemMove",
    itemMoveEnd = "itemMoveEnd",
    /** @deprecated See a documentation: https://docs.dhtmlx.com/ */
    shapeMove = "shapemove"
}
export interface IDiagramEditorHandlersMap {
    [key: string]: (...args: any[]) => any;
    [DiagramEditorEvents.resetButton]: () => void;
    [DiagramEditorEvents.applyButton]: () => void;
    [DiagramEditorEvents.undoButton]: () => void;
    [DiagramEditorEvents.redoButton]: () => void;
    [DiagramEditorEvents.shapeResize]: () => void;
    [DiagramEditorEvents.zoomIn]: () => void;
    [DiagramEditorEvents.zoomOut]: () => void;
    [DiagramEditorEvents.visibility]: () => void;
    [DiagramEditorEvents.exportData]: () => void;
    [DiagramEditorEvents.importData]: (data: any) => void;
    [DiagramEditorEvents.shapesUp]: (shape: any) => void;
    [DiagramEditorEvents.changeGridStep]: (step: number) => void;
    [DiagramEditorEvents.beforeShapeIconClick]: (iconId: string, shape: IDataItem) => boolean | void;
    [DiagramEditorEvents.afterShapeIconClick]: (iconId: string, shape: IDataItem) => void;
    [DiagramEditorEvents.beforeShapeMove]: (events: MouseEvent, id: Id, coord: IBaseCoords) => boolean | void;
    [DiagramEditorEvents.afterShapeMove]: (events: MouseEvent, id: Id, coord: IBaseCoords) => void;
    [DiagramEditorEvents.shapeMoveEnd]: (events: MouseEvent, id: Id, coord: IBaseCoords) => void;
    [DiagramEditorEvents.beforeGroupMove]: (events: MouseEvent, id: Id, coord: IBaseCoords) => boolean | void;
    [DiagramEditorEvents.afterGroupMove]: (events: MouseEvent, id: Id, coord: IBaseCoords) => void;
    [DiagramEditorEvents.groupMoveEnd]: (events: MouseEvent, id: Id, coord: IBaseCoords) => void;
    [DiagramEditorEvents.beforeItemMove]: (events: MouseEvent, id: Id, coord: IBaseCoords, $mov: ICoords) => boolean | void;
    [DiagramEditorEvents.afterItemMove]: (events: MouseEvent, id: Id, coord: IBaseCoords) => void;
    [DiagramEditorEvents.itemMoveEnd]: (events: MouseEvent, id: Id, coord: IBaseCoords) => void;
    /** @deprecated See a documentation: https://docs.dhtmlx.com/ */
    [DiagramEditorEvents.shapeMove]: () => void;
}
