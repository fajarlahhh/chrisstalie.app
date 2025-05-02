import { Callback, IEventSystem } from "../../ts-common/events";
import { Id } from "../../ts-common/types";
import { DataCollection, IDataItem } from "../../ts-data";
import { DataEvents, IItemConfig, IShapeCollectionConfig } from "./types";
export declare class ShapesCollection extends DataCollection {
    config: IShapeCollectionConfig;
    private _groupChildren;
    private _children;
    private _roots;
    constructor(config: IShapeCollectionConfig, events: IEventSystem<DataEvents>);
    getChildren(id: Id, isTree?: boolean): IDataItem[];
    eachChild(id: Id, callback: Callback, isTree?: boolean): void;
    eachParent(id: Id, callback: Callback, self?: boolean): void;
    getNearId(id: Id): Id;
    mapVisible(handler: any): any[];
    getRoots(): Id[];
    getRoot(id: Id): Id;
    protected _removeNested(item: IDataItem): void;
    protected _eachBranch(item: IItemConfig, handler: any, stack: any[]): void;
    protected _parse_data(data: IDataItem[]): void;
    protected _mark_chains(): void;
    protected _setBranchLevel(item: any, level?: number): void;
    private _setGroupChildren;
    private _removeGroupChildren;
    private _setGroupVisable;
    private _checkSwimlaneCells;
}
